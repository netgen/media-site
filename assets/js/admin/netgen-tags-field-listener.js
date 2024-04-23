const selectInputRoot = document.querySelector(
  '[name="ezplatform_content_forms_content_edit[fieldsData][main_topic][ids]"]'
);
const mainTopicParentIdInput = document.querySelector(
  '#ezplatform_content_forms_content_edit_fieldsData_main_topic_value_parent_ids'
);
const mainTopicKeywordInput = document.querySelector(
  '#ezplatform_content_forms_content_edit_fieldsData_main_topic_value_keywords'
);
const mainTopicLocalesInput = document.querySelector(
  '#ezplatform_content_forms_content_edit_fieldsData_main_topic_value_locales'
);

selectInputRoot?.addEventListener('change', (event) => {
  const parentTagId = event.target.selectedOptions[0].getAttribute('data-parentTagId');
  const keyword = event.target.selectedOptions[0].getAttribute('data-keyword');
  const locales = event.target.selectedOptions[0].getAttribute('data-locale');

  mainTopicParentIdInput.value = parentTagId;
  mainTopicKeywordInput.value = keyword;
  mainTopicLocalesInput.value = locales;
});
