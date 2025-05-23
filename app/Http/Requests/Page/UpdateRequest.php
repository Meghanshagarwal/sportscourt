<?php

namespace App\Http\Requests\Page;

use App\Models\CustomPage\PageContent;
use App\Models\Language;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize()
  {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules()
  {
    $ruleArray = [
      'status' => 'required'
    ];


    $defaultLanguage = Language::where('is_default', 1)->first();
    // Default language fields should always be required
    $ruleArray[$defaultLanguage->code . '_title'] = [
      'required',
      'max:255',
      Rule::unique('page_contents', 'title')->ignore($this->id, 'page_id')
    ];

    $ruleArray[$defaultLanguage->code . '_content'] = 'required';


    $languages = Language::all();
    foreach ($languages as $language) {
      $hasExistingContent  = PageContent::where('language_id', $language->id)->where('page_id', $this->id)->exists();
      $code = $language->code;

      // Skip the default language as it's always required
      if ($language->id == $defaultLanguage->id) {
        continue;
      }

      // Check if any field for this language is filled
      if (
        $this->filled($code . '_title') ||
        $hasExistingContent ||
        $this->filled($code . '_content')
      ) {
        $ruleArray[$code . '_title'] = [
          'required',
          'max:255',
          Rule::unique('page_contents', 'title')->ignore($this->id, 'page_id')
        ];
        $ruleArray[$code . '_content'] = 'required';
      }
    }

    return $ruleArray;
  }

  /**
   * Get the validation messages that apply to the request.
   *
   * @return array
   */
  public function messages()
  {
    $messageArray = [];

    $languages = Language::all();

    foreach ($languages as $language) {
      $messageArray[$language->code . '_title.required'] =
      __('The title field is required for') . ' ' . $language->name . ' ' . __('language') . '.';

      $messageArray[$language->code . '_title.max'] = 'The title field cannot contain more than 255 characters for ' . $language->name . ' language.';

      $messageArray[$language->code . '_title.unique'] = 'The title field must be unique for ' . $language->name . ' language.';

      $messageArray[$language->code . '_content.required'] =
      __('The content field is required for') . ' ' . $language->name . ' ' . __('language') . '.';
    }

    return $messageArray;
  }
}
