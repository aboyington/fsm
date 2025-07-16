# Parts & Services Management

## Overview

The Parts and Services module in the FSM platform is designed to manage the inventory of physical parts as well as services provided to customers. This documentation provides details about how the module obtains, stores, and manipulates data related to parts and services.

## Key Components

- **Parts**: This refers to the physical items that are used in service operations, such as cameras, cables, and sensors.
- **Services**: These are intangible offerings like installation, maintenance, and support that the company provides.

## Database Structure

Parts and services are stored in the `product_skus` table with the following fields:

- `sku_code`: A unique identifier for each part or service.
- `category`: Differentiates whether the SKU is a part (`PRT`) or a service (`SRV`).
- `name`: The name of the part or service.
- `description`: Brief details about the part or service.
- `unit_price`: Cost per unit for parts or rate per service.
- `cost_price`: Cost to acquire or provide the item/service.
- `quantity_on_hand`: Available inventory for parts.
- `duration_minutes`: Expected duration for services.
- `status`: Determines the active/inactive state of the SKU.
- `is_active`: Boolean flag to enable or disable the SKU.
- Other fields for parts such as `supplier`, `manufacturer`, `warranty_period`, etc.

## Functionality

### Parts Management

- **Create**: New parts can be created with all necessary details like SKU, name, and cost.
- **Update**: Existing parts can be updated to reflect changes in stock, pricing, or supplier information.
- **Track Inventory**: Monitor quantities and set threshold alerts for low stock.

### Services Management

- **Create**: Similarly, services can be created, with focus on pricing and duration.
- **Update**: Changes in service offerings, rates, and tax applicability can be updated.
- **Analytics**: Dashboard views showing popular services and usage statistics.

## API Endpoints

The FSM platform provides API endpoints to interact with parts and services. The basic operations are:

- `GET /parts-services`: Fetch all parts and services with filtering and search capabilities.
- `POST /parts-services/create`: Create a new part or service.
- `PUT /parts-services/update/{id}`: Update details of a specific part or service.
- `DELETE /parts-services/delete/{id}`: Remove a part or service permanently.

## User Interface

The user interface for the parts and services is designed to be intuitive and allows for quick access to major functions. Filtering options enable users to view specific categories, and all changes are reflected in real time.

## Reports & Insights

Various insights into the usage of parts and services are provided via the dashboard, allowing managers to make informed decisions based on real data.

## Import & Export

The system supports bulk import and export using CSV files. Templates are provided to ensure data consistency.

## Conclusions

The Parts & Services module is an integral part of the FSM platform, providing robust capabilities for managing all aspects of parts and services provisioning.
