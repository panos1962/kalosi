#!/usr/bin/env make -f

JSMINIFIER = yui-compressor

.SILENT:

.PHONY: all
all:
	make min

test:
	make all
	bash local/test.sh

%.min.js: %.js
	$(JSMINIFIER) $< >$@

.PHONY: min
min: www/kalosi.min.js

# GIT SECTION

.PHONY: status
status:
	git status .

.PHONY: diff
diff:
	git diff .

.PHONY: add
add:
	git add --verbose .

.PHONY: showadd
showadd:
	git add --dry-run .

.PHONY: commit
commit:
	git commit --message "modifications" .; :

.PHONY: push
push:
	git push

.PHONY: pull
pull:
	git pull && make

# FILE SECTION

.PHONY: cleanup
cleanup:
	echo "Clean!"
