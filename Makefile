PHP=/usr/bin/php
DIFF=/usr/bin/diff
TEST=test/test.php
EXPECT=test/expect.txt
RESULT=test/results.txt
DIFFRESULT=test/results.diff
SRC=decimal.php

$(RESULT): $(TEST) $(SRC)
	$(PHP) $< > $@

$(DIFFRESULT): $(EXPECT) $(RESULT)
	$(DIFF) $^ > $@

test: $(DIFFRESULT)
	@cat $<
	@test -f $< -a ! -s $< && echo "No differences found, test OK." || echo "Differences in output, test FAILED."

clean:
	-rm -v $(DIFFRESULT) $(RESULT)

.PHONY: test
