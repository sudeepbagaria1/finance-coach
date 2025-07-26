<?php
header('Content-Type: application/json');

$in = json_decode(file_get_contents('php://input'), true);
if (!$in) { http_response_code(400); exit(json_encode(['error'=>'Bad JSON'])); }

$income   = (float)($in['income']   ?? 0);
$expenses = (float)($in['expenses'] ?? 0);
$deps     = (int)  ($in['dependants'] ?? 0);
$age      = (int)  ($in['age'] ?? 25);

$out = [];
$out['emergencyFundTarget'] = $expenses * ($deps > 0 ? 6 : 3);
$out['budget50_20_30'] = [
  'needs'   => round($income * 0.50),
  'savings' => round($income * 0.20),
  'wants'   => round($income * 0.30),
];
$out['retirementEquity']   = max(100 - $age, 0) . '% equity';
$out['carBudget'] = [
  'maxCarCost' => round($income * 10),
  'down20pc'   => round($income * 10 * 0.20),
  'emiCap'     => round($income * 0.10 / 12),
];

echo json_encode($out);