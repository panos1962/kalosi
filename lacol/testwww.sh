#!/usr/bin/env bash

# Το παρόν shell script μπορεί να διευκολύνει κατά πολύ τη διαδικασία ελέγχου
# των αλλαγών που κάνουμε στις ιστοσελίδες μας. Μπορούμε, π.χ. να κάνουμε map
# το πλήκτρο "T" στον vim ώστε να επιτελεί «σώσιμο» στο αρχείο και αμέσως μετά
# να τρέχει το αρχείο "local/test.sh" το οποίο θα έχουμε δημιουργήσει με βάση
# το παρόν.

name="Vivaldi"
wid=$(xdotool search --onlyvisible --name "${name}" | sort -n | head -1)

if [ -n "${wid}" ]; then
	xdotool windowactivate "${wid}"
	xdotool key 'ctrl+r'
	exit 0
fi

url="http://localhost/XXX/index.php"

vivaldi "${url}" &

wid=$(xdotool search --onlyvisible --name "${name}" | head -1)

[ -n "${wid}" ] &&
xdotool windowfocus "${wid}"

exit 0
