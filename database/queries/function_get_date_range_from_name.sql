CREATE OR REPLACE FUNCTION get_date_range_from_name(date_range_name text)
    RETURNS jsonb
    language plpgsql
as
$$
Declare
    from_date timestamp;
    to_date timestamp;
    compare_date timestamp;
    standard_date_range_name text;
    prefix text;
    time_unit text;
BEGIN
    compare_date := now();
    standard_date_range_name := date_range_name;
    CASE date_range_name
        WHEN 'today'
            THEN
                standard_date_range_name := 'this-day';
        WHEN 'yesterday'
            THEN
                standard_date_range_name := 'last-day';
            ELSE
    END CASE;
    prefix := split_part(standard_date_range_name, '-', 1);
    time_unit := split_part(standard_date_range_name, '-', 2);
    CASE prefix
        WHEN 'last'
            THEN
                compare_date := compare_date - ('1 ' || time_unit)::INTERVAL;
            ELSE
    END CASE;
    from_date := date_trunc(time_unit, compare_date);
    to_date := date_trunc(time_unit, compare_date + ('1 ' || time_unit)::INTERVAL) - INTERVAL '1 second';
    RETURN jsonb_build_array(from_date, to_date);
END
$$
;
