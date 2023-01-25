import initializeComponents from './initialize-components';

export default function (componentConfiguration) {
  const boundInitializeComponents = initializeComponents.bind(null, componentConfiguration);

  if (document.readyState !== 'loading') {
    initializeComponents(componentConfiguration);
  } else {
    document.addEventListener('DOMContentLoaded', boundInitializeComponents);
  }

  document.addEventListener('ngl:preview:block:refresh', boundInitializeComponents);
}
