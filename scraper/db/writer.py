import logging
from db.connection import get_connection

logger = logging.getLogger('main.db.writer')

def insert_price_history(product_id, store_name, store_url, price, currency, status):
    """
    Inserts a scraped price record into the price_history table.
    """
    connection = get_connection()
    if not connection:
        logger.error("Failed to get DB connection for writer.")
        return False

    try:
        cursor = connection.cursor()
        
        # Get sp_id
        cursor.execute('''
            SELECT sp.id FROM store_product sp
            JOIN stores s ON s.id = sp.store_id
            WHERE sp.product_id = %s AND s.name = %s
        ''', (product_id, store_name))
        result = cursor.fetchone()
        
        if not result:
            logger.error(f"Store product relation not found for product {product_id} and store {store_name}")
            return False
            
        sp_id = result[0]

        query = """
            INSERT INTO price_history
            (sp_id, price, currency, status)
            VALUES (%s, %s, %s, %s)
        """
        values = (sp_id, price, currency, status)
        cursor.execute(query, values)

        # Keep store_product.product_price in sync with the latest scraped price
        # so that MIN(product_price) across stores always reflects current prices
        if status == 'ok' and price is not None:
            cursor.execute(
                "UPDATE store_product SET product_price = %s WHERE id = %s",
                (price, sp_id)
            )

        # Optionally update URL if needed, although usually handled by sync config
        if store_url:
            cursor.execute("UPDATE store_product SET product_url = %s WHERE id = %s", (store_url, sp_id))
            
        connection.commit()
        return True
    except Exception as e:
        logger.error(f"Failed to insert record for product {product_id} at {store_name}: {e}")
        return False
    finally:
        if connection and connection.is_connected():
            cursor.close()
            connection.close()
