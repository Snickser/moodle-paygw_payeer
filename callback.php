<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     paygw_payeer
 * @copyright   2024 Alex Orlov <snickser@gmail.com>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use core_payment\helper;
use paygw_payeer\notifications;

require("../../../config.php");
global $CFG, $USER, $DB;

require_once($CFG->libdir . '/filelib.php');

defined('MOODLE_INTERNAL') || die();

$status         = required_param('m_status', PARAM_TEXT);
$amount         = required_param('m_amount', PARAM_FLOAT);
$currency       = required_param('m_curr', PARAM_TEXT);
$orderid        = required_param('m_orderid', PARAM_INT);
$signature      = required_param('m_sign', PARAM_TEXT);

$arhash = [
    $_POST['m_operation_id'],
    $_POST['m_operation_ps'],
    $_POST['m_operation_date'],
    $_POST['m_operation_pay_date'],
    $_POST['m_shop'],
    $orderid,
    $amount,
    $currency,
    $_POST['m_desc'],
    $status,
];

if ($status !== 'success') {
    die('FAIL. Payment not successed.');
}

if (!$payeertx = $DB->get_record('paygw_payeer', [ 'paymentid' => $orderid ])) {
    die('FAIL. Not a valid transaction.');
}

if (!$payment = $DB->get_record('payments', ['id' => $payeertx->paymentid])) {
    die('FAIL. Not a valid payment.');
}
$component   = $payment->component;
$paymentarea = $payment->paymentarea;
$itemid      = $payment->itemid;
$paymentid   = $payment->id;
$userid      = $payment->userid;

// Get apikey and secretkey.
$config = (object) helper::get_gateway_configuration($component, $paymentarea, $itemid, 'payeer');

$arhash[] = $config->apikey;

$signhash = strtoupper(hash('sha256', implode(':', $arhash)));

if ($signature !== $signhash) {
    die('FAIL. Signature error.');
}

// Update payment.
$payment->amount = $amount;
$DB->update_record('payments', $payment);

// Deliver order.
helper::deliver_order($component, $paymentarea, $itemid, $paymentid, $userid);

// Notify user.
notifications::notify(
    $userid,
    $payment->amount,
    $payment->currency,
    $paymentid,
    'Success completed'
);

// Update paygw.
$payeertx->success = 1;
if (!$DB->update_record('paygw_payeer', $payeertx)) {
    die('FAIL. Update db error.');
} else {
    die($paymentid . '|success');
}
