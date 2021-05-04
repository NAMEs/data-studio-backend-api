TRANSFER_FOLDER=/home/transfer
DONE_FOLDER=/home/done

mkdir -p $TRANSFER_FOLDER
mkdir -p $DONE_FOLDER

cd $TRANSFER_FOLDER || exit

echo "Found $(ls -1q *.log | wc -l) file(s)"

for FILE in *.log;
  do {
    if [ ! -f "$FILE.lock" ]; then
      echo "Processing $FILE" \
        & touch "$FILE.lock" \
        && {
          cat $FILE | sed 's/\t\-/\t/g' | sed 's/\t000\t/\t0\t/g' | clickhouse-client --input_format_null_as_default=1 --query="INSERT INTO scaleflex_ch.logs_resize FORMAT TSV" \
            && mv $FILE $DONE_FOLDER \
            && echo "Done $FILE, remain $(ls -1q *.log | wc -l) file(s)" \
            && rm "$FILE.lock"
        } || rm "$FILE.lock"
    fi
  };
done

