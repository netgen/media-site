export default {
  EVENTS: {
    CHANGED: 'changed',
    FAILED: 'failed',
    SUBMITTED: 'submitted',
    OPENED: 'opened',
    CANCELLED: 'cancelled',
  },

  push(name, suffix = null) {
    if (name === undefined) {
      console.warn(`GTM push failed: event name is not defined`);

      return;
    }

    let eventName = name;
    if (suffix) {
      eventName += `-${suffix}`;
    }

    if (!('dataLayer' in window)) {
      console.warn(`GTM push failed: data layer is not available (${eventName})`);

      return;
    }

    window.dataLayer.push({ event: eventName });
    console.info(`GTM event pushed: ${eventName}`);
  },
};
