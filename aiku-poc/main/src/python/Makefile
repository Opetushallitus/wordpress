all: clean venv
	venv/bin/python aiku-poc.py

clean:
	rm -rf venv/

venv: dirs venv/bin/activate

venv/bin/activate: requirements.txt
	test -d venv || virtualenv venv
	. venv/bin/activate; venv/bin/pip install -Ur requirements.txt
	touch venv/bin/activate
