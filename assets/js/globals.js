/**
 * IMPORTANT:
 *
 * This file contains only import and assignment of functions that must be global.
 * You will know exactly when and why that is the case.
 *
 * If it's anything else - it does not belong here.
 *
 * Everything added here must be assigned to the window.ngsite object.
 */

// Import
import updateSelectedFileNames from './utils/update-selected-file-names';

// Assignment
window.ngsite = {
  updateSelectedFileNames,
};
