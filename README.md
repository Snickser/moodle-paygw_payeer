[![Donate](https://img.shields.io/badge/Donate-PayPal-green.svg)](https://paypal.me/snickser) support me with a donation.

# Payeer payment gateway plugin for Moodle.

Version 0.1

https://payeer.com

![alt text](https://github.com/Snickser/moodle-paygw_payeer/blob/main/pix/img.svg)

## Status

[![Build Status](https://github.com/Snickser/moodle-paygw_payeer/actions/workflows/moodle-ci.yml/badge.svg)](https://github.com/Snickser/moodle-paygw_payeer/actions/workflows/moodle-ci.yml)

## Возможности

+ Можно использовать пароль или кнопку для обхода платежа.
+ Сохраняет в базе номер курса и название группы студента.
+ Можно указать рекомендуемую цену.
+ Можно ограничить максимальную цену.
+ Отображение продолжительности обучения (для enrol_fee и mod_gwpaymets), если она установлена.
+ Поддержка пароля из модуля курса (mod_gwpaymets).
+ Оповещение пользователя при успешном платеже.


## Рекомендации

+ Moodle 4.3+
+ Для записи в курс подходит стандарный плагин "Зачисление за оплату" (enrol_fee).
+ Для контрольного задания используйте модуль "[Gateway Payments](https://moodle.org/plugins/mod_gwpayments)" (мои правки [mod_gwpayments](https://github.com/Snickser/moodle-mod_gwpayments/tree/dev)), он правда глючный, но других пока нет.
+ Для ограничения доступности используйте модуль "[PaymentS availability condition for paid access](https://moodle.org/plugins/availability_gwpayments)" (мои правки [availability_gwpayments](https://github.com/Snickser/moodle-availability_gwpayments/tree/dev)).


## INSTALLATION

Download the latest **paygw_payeer.zip** and unzip the contents into the **/payment/gateway** directory. Or upload it from Moodle plugins adminnistration interface.<br>

1. Install the plugin
2. Enable the payeer payment gateway
3. Create a new payment account
4. Configure the payment account against the payeer gateway using your pay ID
5. Enable the 'Enrolment on Payment' enrolment method
6. Add the 'Enrolment on Payment' method to your chosen course
7. Set the payment account, enrolment fee, and currency

This plugin supports only basic functionality, but everything changes someday...
