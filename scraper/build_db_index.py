import os
import json
import sqlite3

def init_db(db_path):
    if os.path.exists(db_path):
        os.remove(db_path)
        
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()
    cursor.execute('''
        CREATE TABLE components (
            id TEXT PRIMARY KEY,
            category TEXT,
            name TEXT,
            search_text TEXT,
            specs_json TEXT
        )
    ''')
    # Create an index on search_text for faster matching later
    cursor.execute('CREATE INDEX idx_search_text ON components(search_text)')
    return conn

def scan_and_index(db_conn, db_root_path):
    cursor = db_conn.cursor()
    count = 0
    categories = os.listdir(db_root_path)
    
    for category in categories:
        cat_path = os.path.join(db_root_path, category)
        if not os.path.isdir(cat_path):
            continue
            
        for file in os.listdir(cat_path):
            if not file.endswith('.json'):
                continue
                
            file_path = os.path.join(cat_path, file)
            try:
                with open(file_path, 'r', encoding='utf-8') as f:
                    data = json.load(f)
                    
                    if 'metadata' not in data or 'name' not in data['metadata']:
                        continue
                        
                    name = data['metadata']['name']
                    part_numbers = data['metadata'].get('part_numbers', [])
                    
                    # Create a massive searchable string
                    search_terms = [name.lower()]
                    for pn in part_numbers:
                        if isinstance(pn, str):
                            search_terms.append(pn.lower())
                            
                    search_text = " | ".join(search_terms)
                    file_uuid = file.replace('.json', '')
                    
                    # Dump the entire json back as a string so fetch_specs can parse it
                    specs_json = json.dumps(data)
                    
                    cursor.execute('''
                        INSERT INTO components (id, category, name, search_text, specs_json)
                        VALUES (?, ?, ?, ?, ?)
                    ''', (file_uuid, category, name, search_text, specs_json))
                    count += 1
            except Exception as e:
                print(f"Error parsing {file_path}: {e}")
                
    db_conn.commit()
    print(f"Successfully indexed {count} components into SQLite.")

if __name__ == "__main__":
    base_dir = os.path.dirname(os.path.abspath(__file__))
    source_db_dir = os.path.join(base_dir, 'buildcores-db', 'open-db')
    sqlite_db_path = os.path.join(base_dir, 'components.sqlite')
    
    print(f"Starting indexing from {source_db_dir}...")
    conn = init_db(sqlite_db_path)
    scan_and_index(conn, source_db_dir)
    conn.close()
