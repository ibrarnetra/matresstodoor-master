<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Admin\PageDescription;
use App\Models\Admin\Language;

class Page extends Model
{
    use HasFactory;

    public $language_id;
    public $code;

    public function __construct($code = 'en')
    {
        $this->language_id = ($code == "en") ? "1" : "2";
        $this->code = $code;
    }

    public function descriptions()
    {
        return $this->hasMany(PageDescription::class, 'page_id', 'id');
    }

    public function eng_description()
    {
        return $this->hasOne(PageDescription::class, 'page_id', 'id')->where('language_id', '1');
    }

    function _store($request)
    {
        $page = new Page();
        $page->slug = $request->slug;
        $page->save();

        $page_id = $page->id;

        foreach ($request->page_description as $key => $val) {
            $page_description = new PageDescription();

            $language = (new Language())->getLangByCode($key);

            $page_description->page_id = $page_id;
            $page_description->language_id = $language->id;

            $page_description->title = capAll($val['title']);
            $page_description->content = (isset($val['content']) && !is_null($val['content'])) ? $val['content'] : "";

            $page_description->meta_title = capAll($val['meta_title']);
            $page_description->meta_description = (isset($val['meta_description']) && !is_null($val['meta_description'])) ? $val['meta_description'] : "";
            $page_description->meta_keyword = (isset($val['meta_keyword']) && !is_null($val['meta_keyword'])) ? $val['meta_keyword'] : "";
            $page_description->save();
        }

        return $page_id;
    }

    function _update($request, $id)
    {
        self::where('id', $id)->update([
            "slug" => $request->slug,
        ]);

        foreach ($request->page_description as $key => $val) {
            $language = (new Language())->getLangByCode($key);

            PageDescription::where(['page_id' => $id, 'language_id' => $language->id])->update([
                "title" => capAll($val['title']),
                "content" => (isset($val['content']) && !is_null($val['content'])) ? $val['content'] : "",

                "meta_title" => capAll($val['meta_title']),
                "meta_description" => (isset($val['meta_description']) && !is_null($val['meta_description'])) ? $val['meta_description'] : "",
                "meta_keyword" => (isset($val['meta_keyword']) && !is_null($val['meta_keyword'])) ? $val['meta_keyword'] : "",
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
        $query = self::select('id', 'slug', 'status', 'is_deleted')
            ->where('id', $id)
            ->first();

        return array(
            "id" => $query->id,
            "slug" => $query->slug,
            "status" => $query->status,
            "page_description" => (new PageDescription())->getDescriptionsWithLanguages($id),
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

    function getCmsPage($uri)
    {
        return self::where('slug', $uri)->join('page_descriptions', function ($q) {
            $q->on('page_descriptions.page_id', '=', 'pages.id')->where('page_descriptions.language_id', $this->language_id);
        })
            ->select(
                'pages.*',
                'page_descriptions.title',
                'page_descriptions.content',
                'page_descriptions.meta_title',
                'page_descriptions.meta_keyword',
                'page_descriptions.meta_description',
            )
            ->where('is_deleted', getConstant('IS_NOT_DELETED'))
            ->first();
    }
}
