APP_NAME = DH Catcher

SHELL ?= /bin/bash
ARGS = $(filter-out $@,$(MAKECMDGOALS))

IMAGE_TAG = latest
IMAGE_NAME = dhalkin/catcher

BUILD_ID ?= $(shell /bin/date "+%Y%m%d-%H%M%S")

.SILENT: ;               # no need for @
.ONESHELL: ;             # recipes execute in same shell
.NOTPARALLEL: ;          # wait for this target to finish
.EXPORT_ALL_VARIABLES: ; # send all vars to shell
Makefile: ;              # skip prerequisite discovery

# Run make help by default
.DEFAULT_GOAL = help

ifneq ("$(wildcard ./VERSION)","")
VERSION ?= $(shell cat ./VERSION | head -n 1)
else
VERSION ?= 0.0.1
endif

%.env:
	cp $@.dist $@

# Public targets
.PHONY: .title
.title:
	$(info $(APP_NAME) v$(VERSION))

#.PHONY: build
#build:
#	docker build \
#		--build-arg VERSION=$(VERSION) \
#		--build-arg BUILD_ID=$(BUILD_ID) \
#		-t $(IMAGE_NAME):$(IMAGE_TAG) \
#		--no-cache \
#		--force-rm .

.PHONY: up-arm
up-arm: network
	docker-compose -f docker-compose-arm.yml up -d

.PHONY: install-arm
install-arm: up-arm
	docker-compose -f docker-compose-arm.yml exec app composer install

.PHONY: bash-arm
bash-arm: up-arm
	docker-compose -f docker-compose-arm.yml exec app bash

.PHONY: reset
reset: prune up

.PHONY: down-arm
down:
	docker-compose -f docker-compose-arm.yml down

.PHONY: network
network:
	docker network create catcher-network 2> /dev/null | true

.PHONY: run-command
run-command:
	vendor/bin/phpcs -p --colors
	vendor/bin/codecept run Unit

%:
	@: