# Charging simulator

## Purpose

This is a small simulator that goes with the project. Its purpose is to act like a charging station on which a vehicle is plugged.
As so it periodically update the charging status via the API, allowing easier testing and visualization on how the project work

## Usage

The simulator can be run via a simple **POST** request to it reachable at the route `/charge/start`. Some parameters exist to ensure proper working and customization, namely :

| Parameter      | Required | Description                                                                                                       |
| -------------- | -------- | ----------------------------------------------------------------------------------------------------------------- |
| vehicle_uuid   | ✅       | The vehicle on which to run the simulation                                                                        |
| connector_uuid | ✅       | The connector on which to run the simulation                                                                      |
| starting_chage | ❌       | At which percentage of battery the vehicle start, useful for quicker simulation, random by default                |
| speed          | ❌       | The speed multiplicator to apply on the simulation, by default the simulation run 100 time faster than in reality |

For example, a request via a curl request could look like

```
curl -X POST http://localhost:9000/charge/start \
  -H "Accept: application/json" \
  -d '{
        "vehicleUuid": "8955650f-a06d-4813-bc26-af5a89fce99d",
        "connectorUuid": "46bcb427-a5b3-4899-a198-5f4a5c4889db",
        "startingCharge": 22000,
        "speed": 50
      }'
```

## Side notes

> This simulator need an API_URL and API_KEY environment variables in order to operate properly, these should be set correctly on the container creation.