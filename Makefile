#Makefile for Spinitron Pusher
#PHP doesn't compile, but does get installed
#Suggested usage: git pull
#                 sudo make install

INSTALLDIR = /opt/wmfo/spinitron_push
OWNER = root
MOD = 755
FILES = spinitronPush.php LastFM.php

.PHONY: all install uninstall

all:
	@echo "make: nothing to build for php pages"
	@echo "make: suggested usage: sudo make install"

install: $(addprefix $(INSTALLDIR)/, $(FILES))

$(INSTALLDIR)/%.php: %.php
	@mkdir -p $(INSTALLDIR)
	@cp $< $@
	@chown $(OWNER) $@
	@chmod $(MOD) $@

uninstall:
	for file in $(FILES); do \
	$(RM) $(INSTALLDIR)/$$file ; \
	done
