const presets = [
  [
    '@babel/preset-env',
    {
      targets: {
        ie: '11',
        Safari: '8',
      },
    },
  ],
];

module.exports = { presets };
