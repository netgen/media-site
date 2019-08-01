<?php

namespace Deployer;

/** parameters */
set('slack_text', '_{{user}}_ deploying `{{branch}}` to *{{target}}*');

/** execution */
before('deploy', 'slack:notify');

after('success', 'slack:notify:success');
after('deploy:failed', 'slack:notify:failure');
