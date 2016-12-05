<?php

/*
 * @Author     :   Channaveer Hakari
 * @Email      :   channaveer@sk-access.net
 * @Copyrights :   SK Access Pvt. Ltd.
 */

// SquareInch
function convert_sqinchestosqcms($inch) {
    return ($inch / 0.15500);
}

function convert_sqinchestosqfeet($inch) {
    return ($inch / 144);
}

function convert_sqinchestosqmtr($inch) {
    return ($inch / 1550);
}

function convert_sqinchestosqinches($inch) {
    return ($inch);
}

//SquareCm
function convert_sqcmstosqinches($cm) {
    return ($cm * 0.15500);
}

function convert_sqcmstosqfeet($cm) {
    return ($cm * 0.0010764);
}

function convert_sqcmstosqmtr($cm) {
    return ($cm / 10000);
}

function convert_sqcmstosqcms($cm) {
    return ($cm);
}

//SquareMeter
function convert_sqmtrtosqinches($mtr) {
    return ($mtr * 1550);
}

function convert_sqmtrtosqfeet($mtr) {
    return ($mtr * 10.764);
}

function convert_sqmtrtosqcms($mtr) {
    return ($mtr / 0.00010000);
}

function convert_sqmtrtosqmtr($mtr) {
    return ($mtr);
}

//SquareFeet
function convert_sqfeettosqinches($feet) {
    return ($feet * 144);
}

function convert_sqfeettosqmtr($feet) {
    return ($feet / 10.764);
}

function convert_sqfeettosqcms($feet) {
    return ($feet / 0.0010764);
}

function convert_sqfeettosqfeet($feet) {
    return ($feet);
}

/* Convert from one length unit to another */

// SquareInch
function convert_inchestocm($inch) {
    return ($inch * 2.54);
}

function convert_inchestofeet($inch) {
    return ($inch * 0.0833);
}

function convert_inchestomtr($inch) {
    return ($inch * 0.0254);
}

function convert_inchestoinches($inch) {
    return ($inch);
}

//SquareCm
function convert_cmtoinches($cm) {
    return ($cm * 0.393701);
}

function convert_cmtofeet($cm) {
    return ($cm * 0.0328084);
}

function convert_cmtomtr($cm) {
    return ($cm * 0.01);
}

function convert_cmtocm($cm) {
    return ($cm);
}

//SquareMeter
function convert_mtrtoinches($mtr) {
    return ($mtr * 39.3701);
}

function convert_mtrtofeet($mtr) {
    return ($mtr * 3.28084);
}

function convert_mtrtocm($mtr) {
    return ($mtr * 100);
}

function convert_mtrtomtr($mtr) {
    return ($mtr);
}

//SquareFeet
function convert_feettoinches($feet) {
    return ($feet * 12);
}

function convert_feettomtr($feet) {
    return ($feet * 0.3048);
}

function convert_feettocm($feet) {
    return ($feet * 30.48);
}

function convert_feettofeet($feet) {
    return ($feet);
}

/* Length conversion ends here */

/* Pass length or height to the required unit */

function convertToRequiredArea($length_or_height, $from_unit, $to_unit) {
    $function_name = 'convert_' . $from_unit . 'to' . $to_unit;
    return $function_name($length_or_height);
}

// 4th parameters - feet, inches, mtr, cm
//Converts the area
function convertArea($net_length, $net_height, $from_unit, $to_unit = 'feet') {
    $net_length = convertToRequiredArea($net_length, $from_unit, $to_unit);
    $net_height = convertToRequiredArea($net_height, $from_unit, $to_unit);
    $area = $net_length * $net_height;
    return $area;
}

function getUnitConvertedArea($area = null, $fromUnit = null, $toUnit = null) {
    $functionName = 'convert_' . $fromUnit . 'to' . $toUnit;
    $area = $functionName($area);
    return $area;
}

function getUnitConvertedSlabDimension($dimension = null, $fromUnit = null, $toUnit = null) {
    $functionName = 'convert_' . $fromUnit . 'to' . $toUnit;
    $areaConverted = $functionName($dimension);
    return $areaConverted;
}

function getUnitConvertedDimension($dimension = null, $fromUnit = null, $toUnit = null) {
    $functionName = 'convert_' . $fromUnit . 'to' . $toUnit;
    $areaConverted = $functionName($dimension);
    return $areaConverted;
}