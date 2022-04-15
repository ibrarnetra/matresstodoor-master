<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'The :attribute must be accepted.',
    'active_url' => 'The :attribute is not a valid URL.',
    'after' => 'The :attribute must be a date after :date.',
    'after_or_equal' => 'The :attribute must be a date after or equal to :date.',
    'alpha' => 'The :attribute must only contain letters.',
    'alpha_dash' => 'The :attribute must only contain letters, numbers, dashes and underscores.',
    'alpha_num' => 'The :attribute must only contain letters and numbers.',
    'array' => 'The :attribute must be an array.',
    'before' => 'The :attribute must be a date before :date.',
    'before_or_equal' => 'The :attribute must be a date before or equal to :date.',
    'between' => [
        'numeric' => 'The :attribute must be between :min and :max.',
        'file' => 'The :attribute must be between :min and :max kilobytes.',
        'string' => 'The :attribute must be between :min and :max characters.',
        'array' => 'The :attribute must have between :min and :max items.',
    ],
    'boolean' => 'The :attribute field must be true or false.',
    'confirmed' => 'The :attribute confirmation does not match.',
    'current_password' => 'The password is incorrect.',
    'date' => 'The :attribute is not a valid date.',
    'date_equals' => 'The :attribute must be a date equal to :date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'The :attribute and :other must be different.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'dimensions' => 'The :attribute has invalid image dimensions.',
    'distinct' => 'The :attribute field has a duplicate value.',
    'email' => 'The :attribute must be a valid email address.',
    'ends_with' => 'The :attribute must end with one of the following: :values.',
    'exists' => 'The selected :attribute is invalid.',
    'file' => 'The :attribute must be a file.',
    'filled' => 'The :attribute field must have a value.',
    'gt' => [
        'numeric' => 'The :attribute must be greater than :value.',
        'file' => 'The :attribute must be greater than :value kilobytes.',
        'string' => 'The :attribute must be greater than :value characters.',
        'array' => 'The :attribute must have more than :value items.',
    ],
    'gte' => [
        'numeric' => 'The :attribute must be greater than or equal :value.',
        'file' => 'The :attribute must be greater than or equal :value kilobytes.',
        'string' => 'The :attribute must be greater than or equal :value characters.',
        'array' => 'The :attribute must have :value items or more.',
    ],
    'image' => 'The :attribute must be an image.',
    'in' => 'The selected :attribute is invalid.',
    'in_array' => 'The :attribute field does not exist in :other.',
    'integer' => 'The :attribute must be an integer.',
    'ip' => 'The :attribute must be a valid IP address.',
    'ipv4' => 'The :attribute must be a valid IPv4 address.',
    'ipv6' => 'The :attribute must be a valid IPv6 address.',
    'json' => 'The :attribute must be a valid JSON string.',
    'lt' => [
        'numeric' => 'The :attribute must be less than :value.',
        'file' => 'The :attribute must be less than :value kilobytes.',
        'string' => 'The :attribute must be less than :value characters.',
        'array' => 'The :attribute must have less than :value items.',
    ],
    'lte' => [
        'numeric' => 'The :attribute must be less than or equal :value.',
        'file' => 'The :attribute must be less than or equal :value kilobytes.',
        'string' => 'The :attribute must be less than or equal :value characters.',
        'array' => 'The :attribute must not have more than :value items.',
    ],
    'max' => [
        'numeric' => 'The :attribute must not be greater than :max.',
        'file' => 'The :attribute must not be greater than :max kilobytes.',
        'string' => 'The :attribute must not be greater than :max characters.',
        'array' => 'The :attribute must not have more than :max items.',
    ],
    'mimes' => 'The :attribute must be a file of type: :values.',
    'mimetypes' => 'The :attribute must be a file of type: :values.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'file' => 'The :attribute must be at least :min kilobytes.',
        'string' => 'The :attribute must be at least :min characters.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'multiple_of' => 'The :attribute must be a multiple of :value.',
    'not_in' => 'The selected :attribute is invalid.',
    'not_regex' => 'The :attribute format is invalid.',
    'numeric' => 'The :attribute must be a number.',
    'password' => 'The password is incorrect.',
    'present' => 'The :attribute field must be present.',
    'regex' => 'The :attribute format is invalid.',
    'required' => 'The :attribute field is required.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_unless' => 'The :attribute field is required unless :other is in :values.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_with_all' => 'The :attribute field is required when :values are present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'required_without_all' => 'The :attribute field is required when none of :values are present.',
    'prohibited' => 'The :attribute field is prohibited.',
    'prohibited_if' => 'The :attribute field is prohibited when :other is :value.',
    'prohibited_unless' => 'The :attribute field is prohibited unless :other is in :values.',
    'same' => 'The :attribute and :other must match.',
    'size' => [
        'numeric' => 'The :attribute must be :size.',
        'file' => 'The :attribute must be :size kilobytes.',
        'string' => 'The :attribute must be :size characters.',
        'array' => 'The :attribute must contain :size items.',
    ],
    'starts_with' => 'The :attribute must start with one of the following: :values.',
    'string' => 'The :attribute must be a string.',
    'timezone' => 'The :attribute must be a valid timezone.',
    'unique' => 'The :attribute has already been taken.',
    'uploaded' => 'The :attribute failed to upload.',
    'url' => 'The :attribute must be a valid URL.',
    'uuid' => 'The :attribute must be a valid UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],

        ### ATTRIBUTES ### 
        'attribute_description.*.name' => [
            'required' => 'The attribute name field is required.',
        ],
        'attribute_group_id' => [
            'required' => 'The attribute group field is required.',
        ],

        ### ATTRIBUTE GROUPS ### 
        'attribute_group_description.*.name' => [
            'required' => 'The attribute group name field is required.',
        ],

        ### CATEGORIES ### 
        'category_description.*.name' => [
            'required' => 'The category name field is required.',
        ],
        'category_description.*.meta_title' => [
            'required' => 'The category meta title field is required.',
        ],

        ### CUSTOMERS ###
        'first_name' => [
            'required' => 'The first name field is required.',
        ],
        'last_name' => [
            'required' => 'The last name field is required.',
        ],

        ### CUSTOMER GROUPS ###
        'customer_group.*.name' => [
            'required' => 'The customer group name field is required.',
        ],

        ### LENGTH CLASSES ###
        'length_class_description.*.title' => [
            'required' => 'The length class title field is required.',
        ],
        'length_class_description.*.unit' => [
            'required' => 'The length class unit field is required.',
        ],

        ### OPTIONS ###
        'option_description.*.name' => [
            'required' => 'The option name field is required.',
        ],
        'option_type' => [
            'required' => 'The option type field is required.',
        ],

        ### ORDERS ###
        'currency_id' => [
            'required' => 'The currency field is required.',
        ],
        'customer_group_id' => [
            'required' => 'The customer group field is required.',
        ],
        'customer_id' => [
            'required' => 'The customer field is required.',
        ],

        ### PAYMENT DETAILS ###
        'payment_first_name' => [
            'required' => 'The payment first name field is required.',
        ],
        'payment_last_name' => [
            'required' => 'The payment last name field is required.',
        ],
        'payment_address_1' => [
            'required' => 'The payment address 1 field is required.',
        ],
        'payment_city' => [
            'required' => 'The payment city field is required.',
        ],
        'payment_country_id' => [
            'required' => 'The payment country field is required.',
        ],
        'payment_zone_id' => [
            'required' => 'The payment state field is required.',
        ],

        ### SHIPPING DETAILS ###
        'shipping_first_name' => [
            'required' => 'The shipping first name field is required.',
        ],
        'shipping_last_name' => [
            'required' => 'The shipping last name field is required.',
        ],
        'shipping_address_1' => [
            'required' => 'The shipping address 1 field is required.',
        ],
        'shipping_city' => [
            'required' => 'The shipping city field is required.',
        ],
        'shipping_country_id' => [
            'required' => 'The shipping country field is required.',
        ],
        'shipping_zone_id' => [
            'required' => 'The shipping state field is required.',
        ],

        ### SHIPPING METHOD ###
        'shipping_method_id' => [
            'required' => 'The shipping method field is required.',
        ],

        ### PAYMENT METHOD ###
        'payment_method_id' => [
            'required' => 'The payment method field is required.',
        ],

        ### TOTALS ###
        'sub_total' => [
            'required' => 'The sub total field is required.',
        ],
        'grand_total' => [
            'required' => 'The grand total field is required.',
        ],

        ### CMS PAGES ### 
        'page_description.*.title' => [
            'required' => 'The page title field is required.',
        ],
        'page_description.*.content' => [
            'required' => 'The page content field is required.',
        ],
        'page_description.*.meta_title' => [
            'required' => 'The page meta title field is required.',
        ],

        ### PRODUCTS ###
        'product_description.*.name' => [
            'required' => 'The product name title field is required.',
        ],
        'stock_status_id' => [
            'required' => 'The stock status field is required.',
        ],
        'date_available' => [
            'required' => 'The available date field is required.',
        ],

        ### SETTINGS ###
        'data.config_url' => [
            'required' => 'The store url field is required.',
        ],
        'data.config_meta_title' => [
            'required' => 'The meta title field is required.',
        ],
        'data.config_meta_description' => [
            'required' => 'The meta description field is required.',
        ],
        'data.config_name' => [
            'required' => 'The store name field is required.',
        ],
        'data.config_owner' => [
            'required' => 'The store owner field is required.',
        ],
        'data.config_address' => [
            'required' => 'The store address field is required.',
        ],
        'data.config_email' => [
            'required' => 'The store email field is required.',
        ],
        'data.config_telephone' => [
            'required' => 'The store telephone field is required.',
        ],

        ### WEIGHT CLASSES ###
        'weight_class_description.*.title' => [
            'required' => 'The weight class title field is required.',
        ],
        'weight_class_description.*.unit' => [
            'required' => 'The weight class unit field is required.',
        ],

        ### CUSTOMER ADDRESS ###
        'address.*.first_name' =>  [
            'required' => 'The address first name field is required.'
        ],
        'address.*.last_name' =>  [
            'required' => 'The address last name field is required.'
        ],
        'address.*.address_1' =>  [
            'required' => 'The address 1 field is required.'
        ],
        'address.*.city' =>  [
            'required' => 'The city field is required.'
        ],
        'address.*.country_id' =>  [
            'required' => 'The country field is required.'
        ],
        'address.*.zone_id' =>  [
            'required' => 'The state field is required.'
        ],

        ### DISPATCH MANAGER ###
        'dispatch_manager_id' => [
            'required' => 'The dispatch manager field is required.'
        ],
        'dispatch_comment' => [
            'required' => 'The dispatch comment field is required.'
        ],
        ### CREDIT CARD VALIDATION ###
        'card_number' => [
            'required' => 'The card number field is required.'
        ],
        'card_exp_month' => [
            'required' => 'The card expiration month field is required.'
        ],
        'card_exp_year' => [
            'required' => 'The card expiration year field is required.'
        ],
        'card_cvv' => [
            'required' => 'The card cvv field is required.'
        ],
        ### CHECKOUT ###
        'account_type' => [
            'required' => 'The account type field is required.'
        ],
        ### BILLING ###
        'auth_billing_shipping_address' => [
            'required' => 'The billing shipping address field is required.'
        ],
        'auth_billing_first_name' => [
            'required' => 'The billing first name field is required.'
        ],
        'auth_billing_last_name' => [
            'required' => 'The billing last name field is required.'
        ],
        'auth_billing_address_1' => [
            'required' => 'The billing address 1 field is required.'
        ],
        'auth_billing_city' => [
            'required' => 'The billing city field is required.'
        ],
        'auth_billing_country_id' => [
            'required' => 'The billing country field is required.'
        ],
        'auth_billing_zone_id' => [
            'required' => 'The billing zone field is required.'
        ],
        ### AUTHENTICATED DELIVERY ###
        'auth_delivery_shipping_address' => [
            'required' => 'The delivery shipping address field is required.'
        ],
        'auth_delivery_first_name' => [
            'required' => 'The delivery first name field is required.'
        ],
        'auth_delivery_last_name' => [
            'required' => 'The delivery last name field is required.'
        ],
        'auth_delivery_address_1' => [
            'required' => 'The delivery address 1 field is required.'
        ],
        'auth_delivery_city' => [
            'required' => 'The delivery city field is required.'
        ],
        'auth_delivery_country_id' => [
            'required' => 'The delivery country field is required.'
        ],
        'auth_delivery_zone_id' => [
            'required' => 'The delivery zone field is required.'
        ],
        ### DELIVERY ###
        "delivery_first_name" => [
            'required' => 'The delivery first name field is required.'
        ],
        "delivery_last_name" => [
            'required' => 'The delivery last name field is required.'
        ],
        "delivery_address_1" => [
            'required' => 'The delivery address 1 field is required.'
        ],
        "delivery_city" => [
            'required' => 'The delivery city field is required.'
        ],
        "delivery_country_id" => [
            'required' => 'The delivery country field is required.'
        ],
        "delivery_zone_id" => [
            'required' => 'The delivery zone field is required.'
        ],
        'telephone' => [
            'required' => 'The mobile field is required.',
            'regex' => 'The mobile format is invalid.'
        ],

        'users' => [
            'required' => 'The team members field is required.',
        ],

        ### reCAPTCHA ###
        'captcha' => [
            'required' => 'Captcha error! try again later or contact site admin.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
