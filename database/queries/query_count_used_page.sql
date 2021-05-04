CREATE OR REPLACE FUNCTION count_element_use_query(_query_id uuid)
    RETURNS int
    language plpgsql IMMUTABLE
AS
$$
DECLARE
    _count bigint;
BEGIN
    SELECT count(1) INTO _count FROM "element" WHERE query_id = _query_id;
    RETURN _count;
END;
$$;
----
ALTER TABLE "query"
ADD COLUMN query_count_used_page int GENERATED ALWAYS AS (
    count_element_use_query(query.query_id)
) STORED;

