# app.py
from flask import Flask, request, jsonify
from flask_cors import CORS
from db_connection import get_connection
import random
import string


app = Flask(__name__)
CORS(app, resources={r"/*": {"origins": "https://restobar.losrealespicks.com"}})


@app.route('/api/registro_usuario', methods=['POST'])
def registrar_usuario():
    data = request.json  # Obtener el JSON del request
    if 'codigo_usuario' not in data:
        return jsonify({"error": "No se proporcionó codigo_usuario"}), 400

    codigo_usuario = data['codigo_usuario']  # Aquí se espera que 'codigo_usuario' esté en el JSON
    nombre = data['nombre']
    telefono = data.get('telefono', None)
    ubicacion = data.get('ubicacion', None)

    # Consulta SQL para insertar o actualizar el usuario en la base de datos
    query = """
    INSERT INTO usuarios (codigo_usuario, nombre, telefono, ubicacion)
    VALUES (%s, %s, %s, %s)
    ON DUPLICATE KEY UPDATE telefono = VALUES(telefono), nombre = VALUES(nombre), ubicacion = VALUES(ubicacion)
    """
    connection = get_connection()
    with connection.cursor() as cursor:
        cursor.execute(query, (codigo_usuario, nombre, telefono, ubicacion))
        connection.commit()

    return jsonify({"codigo_usuario": codigo_usuario})





# Obtener número de teléfono
@app.route('/api/usuario_telefono', methods=['GET'])
def obtener_telefono_usuario():
    codigo_usuario = request.args.get('codigo_usuario')
    print(f"Código de usuario recibido en el backend: {codigo_usuario}")  # Verificar el valor recibido en el backend
    
    if not codigo_usuario:
        return jsonify({"error": "No se proporcionó un código de usuario"}), 400
    
    connection = get_connection()
    try:
        with connection.cursor() as cursor:
            cursor.execute("SELECT telefono FROM usuarios WHERE codigo_usuario = %s", (codigo_usuario,))
            usuario = cursor.fetchone()
            if usuario:
                print(f"Teléfono encontrado para el código de usuario {codigo_usuario}: {usuario['telefono']}")
                return jsonify({"telefono": usuario['telefono']}), 200
            else:
                print(f"No se encontró un número de teléfono para el código de usuario {codigo_usuario}")
                return jsonify({"error": "Usuario no encontrado"}), 404
    finally:
        connection.close()


@app.route('/api/actualizar_ubicacion', methods=['POST'])
def actualizar_ubicacion():
    data = request.json
    codigo_usuario = data['codigo_usuario']
    ubicacion = data['ubicacion']

    connection = get_connection()  # Obtener la conexión a la base de datos
    try:
        # Consulta SQL para actualizar la ubicación en la base de datos
        query = """
        UPDATE usuarios SET ubicacion = %s WHERE codigo_usuario = %s
        """
        with connection.cursor() as cursor:
            cursor.execute(query, (ubicacion, codigo_usuario))
            connection.commit()

        return jsonify({"message": "Ubicación actualizada correctamente"}), 200

    except Exception as e:
        return jsonify({"error": str(e)}), 500
    finally:
        connection.close()




# Actualizar número de teléfono
@app.route('/api/actualizar_telefono', methods=['PUT'])
def actualizar_telefono():
    data = request.get_json()
    codigo_usuario = data.get('codigo_usuario')
    nuevo_telefono = data.get('telefono')

    connection = get_connection()
    try:
        with connection.cursor() as cursor:
            cursor.execute(
                "UPDATE usuarios SET telefono = %s WHERE codigo_usuario = %s",
                (nuevo_telefono, codigo_usuario)
            )
            connection.commit()
            return jsonify({"mensaje": "Teléfono actualizado correctamente"}), 200
    finally:
        connection.close()




@app.route('/api/categorias', methods=['GET'])
def get_categorias():
    connection = get_connection()
    try:
        with connection.cursor() as cursor:
            cursor.execute("SELECT * FROM categorias")
            categorias = cursor.fetchall()
        return jsonify(categorias)
    finally:
        connection.close()

@app.route('/api/productos', methods=['GET'])
def get_productos():
    connection = get_connection()
    try:
        with connection.cursor() as cursor:
            cursor.execute("SELECT * FROM productos")
            productos = cursor.fetchall()
        return jsonify(productos)
    finally:
        connection.close()

@app.route('/api/pedidos', methods=['POST'])
def crear_pedido():
    data = request.get_json()
    print("Datos recibidos:", data)

    connection = get_connection()  # Conexión a la base de datos

    try:
        # Vamos a iterar por cada producto en el pedido
        with connection.cursor() as cursor:
            for producto in data['productos']:
                cursor.execute("""
                    INSERT INTO pedidos (producto_id, mozo_id, cantidad, estado, cliente, ubicacion, mesa, nro_orden, codigo_usuario)
                    VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s)
                """, (
                    producto['producto_id'],      # producto_id
                    data['mozo_id'],              # mozo_id
                    producto['cantidad'],         # cantidad
                    'pendiente',                  # estado inicial del pedido
                    data['cliente'],              # cliente
                    data['ubicacion'],            # ubicacion
                    data['mesa'],                 # mesa
                    data['nro_orden'],             # nro_orden
		    data['codigo_usuario']
                ))

        connection.commit()  # Confirmar los cambios
        return jsonify({"mensaje": "Pedido creado correctamente"})

    except KeyError as e:
        print(f"Error al crear el pedido: falta el campo {e}")
        return jsonify({"error": f"Falta el campo: {e}"}), 400
    except Exception as e:
        print(f"Error desconocido al crear el pedido: {e}")
        return jsonify({"error": str(e)}), 500
    finally:
        connection.close()


@app.route('/api/pedidos', methods=['GET'])
def get_pedidos():
    cliente = request.args.get('cliente')
    connection = get_connection()
    try:
        with connection.cursor() as cursor:
            if cliente:
                cursor.execute("SELECT * FROM pedidos WHERE cliente = %s", (cliente,))
            else:
                cursor.execute("SELECT * FROM pedidos")
            pedidos = cursor.fetchall()
        return jsonify(pedidos)
    except Exception as e:
        print("Error al obtener los pedidos:", str(e))
        return jsonify({"error": str(e)}), 500
    finally:
        connection.close()

@app.route('/api/pedidos/estado/<int:nro_orden>', methods=['GET'])
def get_pedido_estado(nro_orden):
    connection = get_connection()
    try:
        with connection.cursor() as cursor:
            cursor.execute("SELECT estado FROM pedidos WHERE nro_orden = %s LIMIT 1", (nro_orden,))
            pedido = cursor.fetchone()
            if pedido:
                return jsonify({'estado': pedido['estado']})
            else:
                return jsonify({'estado': 'Pedido no encontrado'}), 404
    finally:
        connection.close()

@app.route('/api/productos_mas_consumidos', methods=['GET'])
def get_productos_mas_consumidos():
    connection = get_connection()
    try:
        with connection.cursor() as cursor:
            # Selecciona productos únicos, agrupados y ordenados por las veces vendidos
            cursor.execute("""
                SELECT nombre, SUM(pedidos.cantidad) as veces_vendido 
                FROM productos 
                JOIN pedidos ON productos.id = pedidos.producto_id 
                GROUP BY nombre 
                ORDER BY veces_vendido DESC 
                LIMIT 3
            """)
            productos = cursor.fetchall()
        return jsonify({"productos": [producto['nombre'] for producto in productos]})
    finally:
        connection.close()



@app.route('/api/recomendaciones', methods=['GET'])
def obtener_recomendaciones():
    telefono = request.args.get('telefono')
    if not telefono:
        return jsonify({"error": "Falta el teléfono"}), 400

    connection = get_connection()
    try:
        with connection.cursor() as cursor:
            # Obtener recomendaciones basadas en el historial de pedidos del usuario
            cursor.execute("""
                SELECT p.nombre 
                FROM pedidos pd 
                JOIN productos p ON pd.producto_id = p.id 
                JOIN usuarios u ON pd.cliente = u.nombre 
                WHERE u.telefono = %s
            """, (telefono,))
            recomendaciones = cursor.fetchall()

        return jsonify({"recomendaciones": [r['nombre'] for r in recomendaciones]})

    except Exception as e:
        return jsonify({"error": str(e)}), 500

    finally:
        connection.close()


if __name__ == '__main__':
    app.run(debug=True)
