<?php
defined("BASEPATH") or exit("No direct script access allowed");

if (!function_exists("formatTanggal")) {
	function formatTanggal($start, $end)
	{
		$ms = date("n", strtotime($start));
		$ys = date("Y", strtotime($start));
		$ds = date("j", strtotime($start));

		$me = date("n", strtotime($end));
		$ye = date("Y", strtotime($end));
		$de = date("j", strtotime($end));

		$bulan = [
			1 => "Jan",
			"Feb",
			"Mar",
			"Apr",
			"Mei",
			"Jun",
			"Jul",
			"Agu",
			"Sep",
			"Okt",
			"Nov",
			"Des",
		];

		if ($start == $end) {
			return "$de " . $bulan[$me] . " $ye";
		} elseif ($ys == $ye && $ms == $me) {
			return "$ds - $de " . $bulan[$me] . " $ye";
		} elseif ($ys == $ye) {
			return "$ds " . $bulan[$ms] . " - $de " . $bulan[$me] . " $ye";
		} else {
			return "$ds " . $bulan[$ms] . " $ys - $de " . $bulan[$me] . " $ye";
		}
	}
}
