<?php
if (!function_exists('resolveOtherField')) {
    function resolveOtherField(string $field, $request)
    {
        return $request->$field === 'Lainnya' ? $request->input("other_{$field}") : $request->$field;
    }
}
