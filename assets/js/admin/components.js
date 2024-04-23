// This file contains only import, configuration and initialization of components
//import addDocumentEventListeners from '../utilities/components/add-document-event-listeners';

// Import
// import ModalConfirmControl from '../components/ModalConfirmControl.component';
// import SelectCountry from '../components/SelectCountry.component';
// import SelectItem from '../components/SelectItem.component';
// import SelectEmail from '../components/SelectEmail.component';
// import MailPersonalizedAttachmentModal from '../components/MailPersonalizedAttachmentModal.component';
// import DateInput from '../components/DateInput.component';
// import DateRangeInput from '../components/DateRangeInput.component';
// import SelectUserPositionDepartment from '../components/SelectUserPositionDepartment.component';
// import ModalAddUsersToMailGroupControl from '../components/ModalAddUsersToMailGroupControl.component';
// import CheckboxSelectAll from '../components/CheckboxSelectAll.component';
// import ClearFormInputs from '../components/ClearFormInputs.component';
// import ImageUpload from '../components/ImageUpload.component';
// import SelectReload from '../components/SelectReload.component';
// import RangeInputLabel from '../components/RangeInputLabel.component';
// import EnableFilterButtons from '../components/EnableFilterButtons.component';
// import HideOverflowingFilters from '../components/HideOverflowingFilters.component';
// import JavascriptFormHandler from '../components/JavascriptFormHandler.component';
// import CheckboxActionButton from '../components/CheckboxActionButton.component';
// import TogglePlaceholders from '../components/TogglePlaceholders.component';
// import CopyToClipboardTooltip from '../components/CopyToClipboardTooltip.component';
// import CreateMailGroupRequirement from '../components/CreateMailGroupRequirement.component';
// import SelectRecipientsCountry from '../components/SelectRecipientsCountry.component';
// import FormClassUpdater from '../components/FormClassUpdater';
// import FormCollection from '../components/FormCollection.component';
// import ToggleCollapse from '../components/ToggleCollapse.component';
// import LockManager from '../components/LockManager.component';
// import EventStatisticsPDFDownload from '../components/EventStatisticsPDFDownload.component';
// import SurveyAccess from '../components/SurveyAccess.component';

// Configuration
//const componentConfiguration = [
//   {
//     Component: ModalConfirmControl,
//     selector: '.js-modal-confirm',
//   },
//   {
//     Component: ModalAddUsersToMailGroupControl,
//     selector: '.js-modal-add-users-to-mail-group',
//   },
//   {
//     Component: SelectCountry,
//     selector: '.js-country-select',
//   },
//   {
//     Component: SelectItem,
//     selector: '.js-item-select',
//   },
//   {
//     Component: SelectEmail,
//     selector: '.js-email-select',
//   },
//   {
//     Component: MailPersonalizedAttachmentModal,
//     selector:
//       '.js-mail-personalized-preview-modal-trigger, .js-mail-personalized-attachment-modal-trigger',
//   },
//   {
//     Component: DateInput,
//     selector: '.js-date-input',
//   },
//   {
//     Component: DateRangeInput,
//     selector: '.js-date-range-input',
//   },
//   {
//     Component: SelectUserPositionDepartment,
//     selector: '.js-user-position-department-select',
//   },
//   {
//     Component: CheckboxSelectAll,
//     selector: '.js-table-with-checkbox',
//     options: {
//       checkboxSelect: '.js-checkbox-select-all',
//       checkbox: '.js-checkbox',
//     },
//   },
//   {
//     Component: ClearFormInputs,
//     selector: '.js-custom-form',
//     options: {
//       ssSelector: 'select.js-slim-select',
//     },
//   },
//   {
//     Component: ImageUpload,
//     selector: '.js-image-upload',
//     options: {
//       parentFormSelector: '.js-custom-form',
//       removeButtonSelector: '.js-remove-image',
//       filenameWrapper: '.js-filename-wrapper',
//       imageFilenameSelector: '.js-image-filename',
//       replaceImageBtnSelector: '.js-replace-image-btn',
//       imagePreviewSelector: '.js-image-preview',
//     },
//   },
//   {
//     Component: SelectReload,
//     selector: '.js-select-reload',
//   },
//   {
//     Component: RangeInputLabel,
//     selector: '.scale.form-range',
//   },
//   {
//     Component: EnableFilterButtons,
//     selector: '.js-filter-form',
//     options: {
//       hasActiveFilterClass: 'has-active-filter',
//       hasNewInputClass: 'has-new-input',
//     },
//   },
//   {
//     Component: HideOverflowingFilters,
//     selector: '.js-filter-wrapper',
//     options: {
//       filterWrapSelector: '.js-filters',
//     },
//   },
//   {
//     Component: JavascriptFormHandler,
//     selector: '.js-modal-content',
//     options: {
//       formSelector: '.js-ajax-form',
//       modalContentSelector: '.js-modal-content',
//       slimselectSelector: '.js-slimselect',
//       ckeditorSelector: '.js-ckeditor',
//     },
//   },
//   {
//     Component: CheckboxActionButton,
//     selector: '.mail-groups-form-table .js-table-with-checkbox',
//     options: {
//       checkboxSelect: '.js-checkbox-select-all',
//       checkbox: '.js-checkbox',
//       actionButtons: '.mail-group-button-wrap .btn-secondary',
//       actionButtonsWrapper: '.admin-form-action-buttons-wrapper',
//     },
//   },
//   {
//     Component: CheckboxActionButton,
//     selector: '.admin-form-participants',
//     options: {
//       checkbox: '.admin-form-participants .cell-input-wrap input[type="checkbox"]',
//       actionButtons: '.btn-action',
//       actionButtonsWrapper: '.admin-form-action-buttons-wrapper',
//     },
//   },
//   {
//     Component: TogglePlaceholders,
//     selector: '.mail-group-input #togglePlaceholders',
//     options: {
//       placeholdersList: '.mail-placeholders-list',
//       placeholdersToggleLabel: '.show-placeholders-label',
//     },
//   },
//   {
//     Component: CopyToClipboardTooltip,
//     selector: '.mail-placeholders-list li',
//   },
//   {
//     Component: CreateMailGroupRequirement,
//     selector: '.mail-groups-form-table .js-table-with-checkbox.mail-groups-create',
//     options: {
//       checkbox: '.js-checkbox',
//       createNewGroupButton: '.mail-group-create-requirement',
//     },
//   },
//   {
//     Component: SelectRecipientsCountry,
//     selector: '#filter_selections_country',
//   },
//   {
//     Component: FormClassUpdater,
//     selector: '.js-filters .form-select',
//   },
//   {
//     Component: FormCollection,
//     selector: '.js-form-collection',
//   },
//   {
//     Component: ToggleCollapse,
//     selector: '.btn-toggle[data-toggle="collapse"]',
//   },
//   {
//     Component: LockManager,
//     selector: '[data-locked-form]',
//   },
//   {
//     Component: EventStatisticsPDFDownload,
//     selector: '.event-statistics-container',
//     options: {
//       downloadPdfButton: '.pdf-download-btn',
//       chartWrapper: '.js-chart-wrapper',
//     },
//   },
//   {
//     Component: SurveyAccess,
//     selector: '.survey-access-table',
//     options: {
//       row: '.survey-access-row',
//       giveAccessForm: 'form[name="give-access"]',
//       removeAccessForm: 'form[name="remove-access"]',
//     },
//   },
// ];

//addDocumentEventListeners(componentConfiguration);
