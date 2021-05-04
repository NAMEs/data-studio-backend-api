CREATE OR REPLACE FUNCTION get_params_from_query(query text)
    RETURNS jsonb
    language plpgsql IMMUTABLE
AS
$$
BEGIN
    RETURN array_to_json(
            ARRAY(
                    SELECT UNNEST(REGEXP_MATCHES(query, '(?:[^:]:|[^@]@)([a-zA-Z0-9_]+)', 'g'))
                )
        );

END;
$$;
----
ALTER TABLE "query"
ADD COLUMN query_parameters jsonb GENERATED ALWAYS AS (
        get_params_from_query(query."query")
) STORED;

