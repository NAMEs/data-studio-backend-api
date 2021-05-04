CREATE OR REPLACE FUNCTION count_element_use_connection(_connection_string_id uuid)
    RETURNS int
    language plpgsql IMMUTABLE
AS
$$
DECLARE
    _count bigint;
BEGIN
    SELECT count(1) INTO _count FROM "element" WHERE connection_string_id = _connection_string_id;
    RETURN _count;
END;
$$;
----
ALTER TABLE "connection_string"
    ADD COLUMN connection_string_count_used_document int GENERATED ALWAYS AS (
        count_element_use_connection(connection_string.connection_string_id)
    ) STORED;

