export default function (componentConfiguration) {
  componentConfiguration.forEach((configuration) => {
    document
      .querySelectorAll(configuration.selector)
      .forEach((element) => new configuration.Component(element, configuration.options));
  });
}
