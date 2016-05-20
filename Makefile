.PHONY: test

test: test-spec test-integration

test-spec:
	./vendor/bin/phpspec run

test-integration:
	./vendor/bin/phpunit