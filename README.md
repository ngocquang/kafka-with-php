# Kafka with PHP

This Repo provides instructions on how to set up and use Kafka with PHP.

## Start Kafka

```bash
docker network create my-network
docker-compose up -d
```

## Kafka PHP

```bash
# Start Consumer (in new terminal)
docker exec -it kafka-php bash -c "php /var/www/html/consumer.php"

# Start producer (in new terminal)
docker exec -it kafka-php bash -c "php /var/www/html/producer.php"
```

## Kafka CLI

```bash
# Create topic
docker exec kafka kafka-topics --bootstrap-server kafka:9092 \
--create --topic test_topic_1 --partitions 1 --replication-factor 1

# List topics
docker exec kafka kafka-topics --bootstrap-server kafka:9092 \
--list

# Describe topic
docker exec kafka kafka-topics --bootstrap-server kafka:9092 \
--describe --topic test_topic_1

# Produce message
docker exec kafka \
bash -c "echo 'Hello World!' | kafka-console-producer --request-required-acks 1 --broker-list localhost:9092 --topic test_topic_1"

docker exec kafka \
bash -c "seq 42 | kafka-console-producer --request-required-acks 1 --broker-list localhost:9092 --topic test_topic_1 && echo 'Produced 42 messages.'"


# Consume message
docker exec kafka \
kafka-console-consumer --bootstrap-server localhost:9092 --topic test_topic_1 --group my-first-application

# Unless specifying the --from-beginning option, only future messages will be displayed and read.
docker exec kafka \
kafka-console-consumer --bootstrap-server localhost:9092 --topic test_topic_1 --from-beginning --max-messages 42 --group my-first-application
```
