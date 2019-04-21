<?php return array (
  'manifest-version' => '1.1',
  'manifest-attributes' => 
  array (
    'changelog' => 'This file shows the changes in recent releases of mspBePaid.

mspBePaid 1.1.3-pl (February 9, 2016)
====================================
- Fixed lexicons for system settings

mspBePaid 1.1.2-pl (October 8, 2015)
====================================
- Added translates of system setting for English
- Fixed some minor bugs

mspBePaid 1.1.1-pl (October 8, 2015)
====================================
- Fixed resolver for install options

mspBePaid 1.1.0-pl (October 7, 2015)
====================================
- Moved order description to lexicon for easy tuning
- Added options for tune statuses after successful or failed payment request
- Sorted system setting, for less mess
- Added license info to all files of component
- Added script for install package from cli, for dev purposes
- Test mode by default, fixes in setup options
- Rewritten resolver for system settings
- Rewritten handler of answers from payment system
- Added method for calc right units of amount for checkout
- Added country for payments by default (bePaid required country for billing data)

mspBePaid 1.0.6-beta (July 16, 2015)
====================================
- Added require of minimal php version

mspBePaid 1.0.6-beta (July 16, 2015)
====================================
- Copy of mspWebPay
',
    'license' => 'The MIT License (MIT)

Copyright (c) 2015 Ivan Klimchuk <ivan@klimchuk.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.',
    'readme' => 'Payment\'s plugin for e-commerce based on miniShop2 through the bePaid payment system.

Details and docs there: http://docs.modx.pro/components/minishop2/payment-modules/mspbepaid
',
    'requires' => 
    array (
      'php' => '>=5.5',
      'modx' => '>=2.3',
      'miniShop2' => '>=2.1',
    ),
    'setup-options' => 'mspbepaid-1.1.3-pl/setup-options.php',
  ),
  'manifest-vehicles' => 
  array (
    0 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '9617ddac965369755bee9127818fa7c1',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/e801e384bf2b143db6cbd50e3f63ba41.vehicle',
    ),
    1 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'bce76a6b12536b96c77ce7c54be41a0b',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/e2326254945c91afa36f5e3b27f67902.vehicle',
    ),
    2 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'b1897d3abaa6306b81dd91e8d8527c57',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/0b69085ea4b319aacbf74825a9b860ff.vehicle',
    ),
    3 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '48c1f755224e79ebf75dd91f730f4425',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/736ca5c2b835ffa251786eada509a4f9.vehicle',
    ),
    4 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '0a083b863d0997918db23f6a46612d10',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/39e7b5470dd50d6ddcf62e6a588c7d62.vehicle',
    ),
    5 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'a6cd404a868c131543721735ee84c3a2',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/eec1e7fe8ff5fc107132b0dc0e0e305a.vehicle',
    ),
    6 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '367f081eadbc7aadd0676cb4064b9e6c',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/aa4dfb1f3154fbecffb4bbb83c395a9e.vehicle',
    ),
    7 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '83aa00150574631ac32a4bc8aa33d87c',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/63d6ac2f6de34539c07d3622caf651c0.vehicle',
    ),
    8 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'd8746d698939ed8e0e3ed5eb47ec8441',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/1882ff9869409637800061974e879f33.vehicle',
    ),
    9 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '7dbfa06f405db34a9dff5b2da3f3fcc7',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/29c1440cc0f4ed017a17af2e35339889.vehicle',
    ),
    10 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => 'ff0a0568bbd59188851e1db48b812cfc',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/eb24eb1398a656b39f1831dff313c720.vehicle',
    ),
    11 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '14a16b06ddd3376f0c02c097fcd6171d',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/573826b69c4ce354f0e68a45131464e0.vehicle',
    ),
    12 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '6600e8ff4291e1d30402312ab8aa36f8',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/43f6b1ca20a5405a437b093a7f841c74.vehicle',
    ),
    13 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modSystemSetting',
      'guid' => '3d50caec07c6e079d3183a596c33dfbe',
      'native_key' => NULL,
      'filename' => 'modSystemSetting/c220da5790feccbdb4cf24cc4253e7f1.vehicle',
    ),
    14 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'msPayment',
      'guid' => '167b9b737602aa178eb8380b77817c2d',
      'native_key' => NULL,
      'filename' => 'msPayment/83c773f6c14d58b5cd790bd045405bfc.vehicle',
    ),
    15 => 
    array (
      'vehicle_package' => 'transport',
      'vehicle_class' => 'xPDOObjectVehicle',
      'class' => 'modCategory',
      'guid' => 'd66b4365d081f376ed458982348c7f99',
      'native_key' => NULL,
      'filename' => 'modCategory/c33994d3b0b72aba4d6abd906314e267.vehicle',
    ),
  ),
);