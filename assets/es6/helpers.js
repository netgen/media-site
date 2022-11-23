export const findAncestor = (el, sel) => {
    while ((el = el.parentElement) && !((el.matches || el.matchesSelector).call(el,sel)));
    return el;
};

/* get next sibling, optionaly filtered by selector */
export const getNextSibling = (elem, selector) => {
  var sibling = elem.nextElementSibling;
  if (!selector) return sibling;
  while (sibling) {
    if (sibling.matches(selector)) return sibling;
    sibling = sibling.nextElementSibling;
  }
};

/* get next sibling, optionaly filtered by selector */
export const getPreviousSibling = (elem, selector) => {
  var sibling = elem.previousElementSibling;
  if (!selector) return sibling;
  while (sibling) {
    if (sibling.matches(selector)) return sibling;
    sibling = sibling.previousElementSibling;
    }
};

export const elementExists = (el) => {
    if(typeof(el) != 'undefined' && el != null){
        return true;
    } else{
        return false;
    }
};

export const throttle = (fn, wait) => {
    let time = Date.now();
    return (() => {
        if ((time + wait - Date.now()) < 0) {
            fn();
            time = Date.now();
        }
    });
};

export function debounce(func, wait, immediate) {
    var timeout;

    return function executedFunction() {
      var context = this;
      var args = arguments;

      var later = function() {
        timeout = null;
        if (!immediate) func.apply(context, args);
      };

      var callNow = immediate && !timeout;

      clearTimeout(timeout);

      timeout = setTimeout(later, wait);

      if (callNow) func.apply(context, args);
    };
};
