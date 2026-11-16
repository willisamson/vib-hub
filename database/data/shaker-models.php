<?php

/*
 * Beispielauslegung: elektrodynamische Schwingprüfanlagen der RMS Dynamic Test Systems (Reinbek).
 * Werte laut Herstellerdatenblatt (Abruf 11/2026), ohne Gewähr – im Admin unter „Shaker-Modelle“ prüfen und pflegen.
 * Kräfte in kN (Sinus Spitze, Random effektiv, Halbsinus-Schock Spitze), Weg Spitze-Spitze in mm,
 * Geschwindigkeit Spitze in m/s, bewegte Masse (Anker) und max. Last in kg.
 */
return [
    ['name' => 'SW1512',          'sine' => 2.9, 'random' => 2.2, 'shock' => 5,   'disp' => 25.4, 'disp_shock' => 25.4, 'vel' => 2, 'vel_shock' => 2,   'moving' => 3,   'load' => 30,   'f_min' => 3, 'f_max' => 5000, 'table' => 120, 'cooling' => 'luft'],
    ['name' => 'SW3710',          'sine' => 7.5, 'random' => 6.7, 'shock' => 15,  'disp' => 36,   'disp_shock' => 51,   'vel' => 2, 'vel_shock' => 3,   'moving' => 9,   'load' => 250,  'f_min' => 2, 'f_max' => 3000, 'table' => 224, 'cooling' => 'luft'],
    ['name' => 'SW8300-24',       'sine' => 24,  'random' => 24,  'shock' => 48,  'disp' => 36,   'disp_shock' => 51,   'vel' => 2, 'vel_shock' => 3,   'moving' => 22,  'load' => 300,  'f_min' => 5, 'f_max' => 2500, 'table' => 284, 'cooling' => 'luft'],
    ['name' => 'SW8100 (28 kN)',  'sine' => 28,  'random' => 28,  'shock' => 45,  'disp' => 36,   'disp_shock' => 51,   'vel' => 2, 'vel_shock' => 3,   'moving' => 23,  'load' => 300,  'f_min' => 5, 'f_max' => 2500, 'table' => 284, 'cooling' => 'wasser'],
    ['name' => 'SW8500 (45 kN)',  'sine' => 45,  'random' => 45,  'shock' => 105, 'disp' => 51,   'disp_shock' => 76,   'vel' => 2, 'vel_shock' => 2.5, 'moving' => 38,  'load' => 500,  'f_min' => 5, 'f_max' => 2500, 'table' => 400, 'cooling' => 'wasser'],
    ['name' => 'SW9100 (80 kN)',  'sine' => 80,  'random' => 80,  'shock' => 160, 'disp' => 51,   'disp_shock' => 76,   'vel' => 2, 'vel_shock' => 3,   'moving' => 53,  'load' => 500,  'f_min' => 5, 'f_max' => 2500, 'table' => 435, 'cooling' => 'wasser'],
    ['name' => 'SW9500 (120 kN)', 'sine' => 120, 'random' => 120, 'shock' => 240, 'disp' => 51,   'disp_shock' => 76,   'vel' => 2, 'vel_shock' => 2.5, 'moving' => 100, 'load' => 1000, 'f_min' => 5, 'f_max' => 2300, 'table' => 550, 'cooling' => 'wasser'],
    ['name' => 'SW9600',          'sine' => 150, 'random' => 150, 'shock' => 380, 'disp' => 51,   'disp_shock' => 76,   'vel' => 2, 'vel_shock' => 2.5, 'moving' => 110, 'load' => 1000, 'f_min' => 5, 'f_max' => 2300, 'table' => 600, 'cooling' => 'wasser'],
];
