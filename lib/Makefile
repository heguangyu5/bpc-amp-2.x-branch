libamp.so:
	./bpc-prepare.sh src.list
	$(MAKE) -C ./Amp libamp

libamp:
	bpc -v \
		-c bpc.conf  \
		-l amp \
		-u react-promise \
		--input-file src.list

install-libamp:
	cd Amp && bpc -l amp --install
