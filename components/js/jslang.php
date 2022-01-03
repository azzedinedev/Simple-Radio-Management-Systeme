<?php
set_include_path('../..' . PATH_SEPARATOR . get_include_path());
include_once '../captions.php';

header('Content-Type: application/json');

$captions = Captions::getInstance('UTF-8');
$codes = array(
    'DeleteUserConfirmation',
    'And',
    'Ok',
    'CalendarMonths',
    'CalendarMonthsShort',
    'CalendarWeekdays',
    'CalendarWeekdaysShort',
    'CalendarWeekdaysMin',
    'Cancel',
    'Commit',
    'ErrorsDuringUpdateProcess',
    'PasswordChanged',
    'Equals',
    'DoesNotEquals',
    'IsLessThan',
    'IsLessThanOrEqualsTo',
    'IsGreaterThan',
    'IsGreaterThanOrEqualsTo',
    'Like',
    'IsBlank',
    'IsNotBlank',
    'IsLike',
    'IsNotLike',
    'Contains',
    'DoesNotContain',
    'BeginsWith',
    'EndsWith',
    'OperatorAnd',
    'OperatorOr',
    'OperatorNone',
    'Loading',
    'FilterBuilder',
    'DeleteSelectedRecordsQuestion',
    'DeleteRecordQuestion',
    'FilterOperatorEquals',
    'FilterOperatorDoesNotEqual',
    'FilterOperatorIsGreaterThan',
    'FilterOperatorIsGreaterThanOrEqualTo',
    'FilterOperatorIsLessThan',
    'FilterOperatorIsLessThanOrEqualTo',
    'FilterOperatorIsBetween',
    'FilterOperatorIsNotBetween',
    'FilterOperatorContains',
    'FilterOperatorDoesNotContain',
    'FilterOperatorBeginsWith',
    'FilterOperatorEndsWith',
    'FilterOperatorIsLike',
    'FilterOperatorIsNotLike',
    'FilterOperatorIsBlank',
    'FilterOperatorIsNotBlank',
    'FilterOperatorDateEquals',
    'FilterOperatorDateDoesNotEqual',
    'FilterOperatorYearEquals',
    'FilterOperatorYearDoesNotEqual',
    'FilterOperatorMonthEquals',
    'FilterOperatorMonthDoesNotEqual',
    'FilterOperatorIn',
    'FilterOperatorInShort',
    'FilterOperatorNotIn',
    'FilterOperatorNotInShort',
    'FilterOperatorToday',
    'FilterOperatorTodayShort',
    'FilterOperatorThisMonth',
    'FilterOperatorThisMonthShort',
    'FilterOperatorPrevMonth',
    'FilterOperatorPrevMonthShort',
    'FilterOperatorThisYear',
    'FilterOperatorThisYearShort',
    'FilterOperatorPrevYear',
    'FilterOperatorPrevYearShort',
    'Select2MatchesOne',
    'Select2MatchesMoreOne',
    'Select2NoMatches',
    'Select2AjaxError',
    'Select2InputTooShort',
    'Select2InputTooLong',
    'Select2SelectionTooBig',
    'Select2LoadMore',
    'Select2Searching',
    'SaveAndInsert',
    'SaveAndBackToList',
    'SaveAndEdit',
    'Save',
    'MultipleColumnSorting',
    'Column',
    'Order',
    'Sort',
    'AddLevel',
    'DeleteLevel',
    'Ascending',
    'SortBy',
    'ThenBy',
    'Descending',
    'Close',
    'ApplyAdvancedFilter',
    'ResetAdvancedFilter',
    'DisableFilter',
    'EnableFilter',
    'InactivityTimeoutExpired',
);

$resource = array();
foreach ($codes as $code) {
    $resource[$code] = $captions->GetMessageString($code);
}

echo SystemUtils::ToJSON(array(
    'translations' => $resource,
    'firstDayOfWeek' => GetFirstDayOfWeek(),
));
