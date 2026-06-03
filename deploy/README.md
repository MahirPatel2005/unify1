# Aiven + Render deploy

1. In Aiven console → your MySQL service → **Connection information** → download **CA certificate**.
2. Save it as `deploy/ca.pem` in this project.
3. Import local database (see main deploy steps).
4. Set the same values as environment variables on Render.
