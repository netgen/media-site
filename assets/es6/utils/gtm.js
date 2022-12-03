export default {
  push(prefix, suffix) {
    if (!prefix) {
      console.warn(`GTM push failed: prefix is not defined (${suffix})`);

      return;
    }

    const eventName = `${prefix}-${suffix}`;

    if (!('dataLayer' in window)) {
      console.warn(`GTM push failed: data layer is not available (${eventName})`);

      return;
    }

    window.dataLayer.push({ event: eventName });
    console.info(`GTM event pushed: ${eventName}`);
  }
}
