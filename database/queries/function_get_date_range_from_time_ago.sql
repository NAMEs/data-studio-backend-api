CREATE OR REPLACE FUNCTION get_date_range_from_time_ago(year int, month int, day int, hour int, minute int, second int)
    RETURNS jsonb
    language plpgsql
as
$$
Declare
    from_date timestamp;
    to_date timestamp;
BEGIN
    to_date := now();
    from_date := to_date;

    IF year IS NOT NULL
    THEN
        from_date := from_date - (year * INTERVAL '1 year');
    END IF;

    IF month IS NOT NULL
    THEN
        from_date := from_date - (month * INTERVAL '1 month');
    END IF;

    IF day IS NOT NULL
    THEN
        from_date := from_date - (day * INTERVAL '1 day');
    END IF;

    IF hour IS NOT NULL
    THEN
        from_date := from_date - (hour * INTERVAL '1 hour');
    END IF;

    IF minute IS NOT NULL
    THEN
        from_date := from_date - (minute * INTERVAL '1 minute');
    END IF;

    IF second IS NOT NULL
    THEN
        from_date := from_date - (second * INTERVAL '1 second');
    END IF;
    RETURN jsonb_build_array(from_date, to_date);
END
$$
;
