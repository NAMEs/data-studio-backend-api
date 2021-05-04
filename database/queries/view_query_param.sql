SELECT
    element.page_id,
    jsonb_array_elements_text(element.element_config->'queryParameter') AS query_param_key,
    jsonb_array_elements_text(get_date_range_from_name(element.element_config->'defaultValue'->>'value')) AS query_param_default_value
FROM element
WHERE element.element_type::text = 'filter'::text
  AND element.element_config IS NOT NULL
  AND element.element_config->>'filterType' = 'daterange'::text
  AND element.element_config->'defaultValue'->>'type' = 'predefined'
UNION
SELECT
    element.page_id,
    JSONB_ARRAY_ELEMENTS_TEXT(element.element_config ->'queryParameter') AS query_param_key,
    jsonb_array_elements_text(get_date_range_from_time_ago(
            (element.element_config->'defaultValue'->'value'->>'year')::int,
            (element.element_config->'defaultValue'->'value'->>'month')::int,
            (element.element_config->'defaultValue'->'value'->>'day')::int,
            (element.element_config->'defaultValue'->'value'->>'hour')::int,
            (element.element_config->'defaultValue'->'value'->>'minute')::int,
            (element.element_config->'defaultValue'->'value'->>'second')::int
        )) AS query_param_default_value
FROM element
WHERE element.element_type::text = 'filter'::text
  AND element.element_config IS NOT NULL
  AND element.element_config->>'filterType' = 'daterange'::text
  AND element.element_config->'defaultValue'->>'type' = 'ago'
UNION

SELECT
    element.page_id,
    JSONB_ARRAY_ELEMENTS_TEXT(element.element_config ->'queryParameter') AS query_param_key,
    JSONB_ARRAY_ELEMENTS_TEXT((element.element_config -> 'defaultValue'::text) -> 'value'::text)      AS query_param_default_value
FROM element
WHERE element.element_type::text = 'filter'::text
  AND element.element_config IS NOT NULL
  AND element.element_config->>'filterType' = 'daterange'::text
  AND element.element_config->'defaultValue'->>'type' = 'fixed'

UNION

SELECT
    element.page_id,
    element.element_config->> 'queryParameter' AS query_param_key,
    element.element_config->>'defaultValue'      AS query_param_default_value
FROM element
WHERE element.element_type::text = 'filter'::text
  AND element.element_config IS NOT NULL
  AND (element.element_config->>'filterType') = 'text'::text
