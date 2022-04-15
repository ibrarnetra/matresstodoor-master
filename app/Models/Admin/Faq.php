<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public $language_id;
    public $code;

    public function __construct($code = 'en')
    {
        $this->language_id = ($code == "en") ? "1" : "2";
        $this->code = $code;
    }

    public function descriptions()
    {
        return $this->hasMany(FaqDescription::class, 'faq_id', 'id');
    }

    public function eng_description()
    {
        return $this->hasOne(FaqDescription::class, 'faq_id', 'id')->where('language_id', '1');
    }

    function _store($request)
    {
        $faq = new Faq();
        $faq->sort_order = (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : "1";
        $faq->save();

        $faq_id = $faq->id;

        foreach ($request->faq_description as $key => $val) {
            $faq_description = new FaqDescription();

            $language = (new Language())->getLangByCode($key);

            $faq_description->faq_id = $faq_id;
            $faq_description->language_id = $language->id;

            $faq_description->question = $val['question'];
            $faq_description->answer = $val['answer'];

            $faq_description->save();
        }

        return $faq_id;
    }

    function _update($request, $id)
    {
        $faq = Faq::where('id', $id)->first();

        self::where('id', $id)->update([
            "sort_order" => (isset($request->sort_order) && !is_null($request->sort_order)) ? $request->sort_order : $faq->sort_order,
        ]);

        foreach ($request->faq_description as $key => $val) {
            $language = (new Language())->getLangByCode($key);

            FaqDescription::where(['faq_id' => $id, 'language_id' => $language->id])->update([
                "question" => $val['question'],
                "answer" => $val['answer'],
            ]);
        }

        return $id;
    }

    function _destroy($id)
    {
        return self::where('id', $id)->update(['is_deleted' => getConstant('IS_DELETED')]);
    }

    function fetchData($id)
    {
        $query = self::select('id', 'sort_order', 'status', 'is_deleted')
            ->where('id', $id)
            ->first();

        return array(
            "id" => $query->id,
            "sort_order" => $query->sort_order,
            "status" => $query->status,
            "faq_description" => (new FaqDescription())->getDescriptionsWithLanguages($id),
        );
    }

    function _dataTable($request)
    {
    }

    function _updateStatus($request, $id)
    {
        $current_status = $request->input('current_status');

        if ($current_status == getConstant('IS_STATUS_ACTIVE')) {
            $new_status = getConstant('IS_NOT_STATUS_ACTIVE');
        } else {
            $new_status = getConstant('IS_STATUS_ACTIVE');
        }

        $update = self::where(['id' => $id])->update(['status' => $new_status]);

        if ($update) {
            $return = array(['status' => true, 'current_status' => $new_status]);
            $res = json_encode($return);
        } else {
            $return = array(['status' => false, 'current_status' => $new_status]);
            $res = json_encode($return);
        }
        return $res;
    }

    function _bulkDelete($request)
    {
        // return $request;
        $res = ['status' => true, 'message' => 'Success'];
        $deleted = self::whereIn('id', $request->ids)->update(['is_deleted' => getConstant('IS_DELETED')]);
        if (!$deleted) {
            $res['status'] = false;
            $res['message'] = "Error";
        }
        return $res;
    }
}
