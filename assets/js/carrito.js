let cart = [];

loadCart(); // Cargar el carrito desde localStorage al abrir la página
updateCartDisplay(); // Mostrar el carrito al cargar

function toggleCart() {
  document.getElementById('cart').classList.toggle('hidden');
}

// Cambiado: ahora acepta idDetalleProducto
function addToCart(name, price, id_detalles_productos, imageUrl, talla, color, idProducto) {
  price = Number(price);

  // Validar stock antes de agregar
  fetch('envio/validar_stock_tienda.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify([{
      id_detalles_productos: Number(id_detalles_productos),
      name,
      talla,
      color,
      quantity: 1 // Solo queremos saber si hay al menos 1
    }])
  })
  .then(res => res.json())
  .then(data => {
    if (data.sin_stock && data.sin_stock.length > 0 && data.sin_stock[0].stock_disponible <= 0) {
      Swal.fire({
        icon: 'error',
        title: 'Producto agotado',
        text: 'Este producto está agotado y no se puede agregar al carrito.'
      });
      return;
    }

    // Si hay stock, agregar normalmente
    const existingItem = cart.find(item =>
      item.id_detalles_productos == id_detalles_productos &&
      item.talla == talla &&
      item.color == color
    );
    if (existingItem) {
      existingItem.quantity++;
    } else {
      cart.push({
        id_detalles_productos: Number(id_detalles_productos),
        idProducto,
        name,
        price,
        quantity: 1,
        imageUrl,
        talla: talla || '',
        color: color || ''
      });
    }
    saveCart();
    updateCartDisplay();
    actualizarContadorCarrito();

    // Mostrar mini carrito por 2.5 segundos
    const miniCart = document.getElementById('cart');
    if (miniCart) {
      miniCart.classList.remove('hidden');
      setTimeout(() => {
        miniCart.classList.add('hidden');
      }, 2500);
    }

    // SweetAlert de producto agregado
    Swal.fire({
      icon: 'success',
      title: '¡Producto agregado!',
      text: 'El producto fue añadido al carrito.',
      timer: 1500,
      showConfirmButton: false
    });
  });
}

function updateCartDisplay() {
  // Mini-carrito (header)
  const miniCartList = document.getElementById('cart-items');
  if (miniCartList) {
    miniCartList.innerHTML = '';
    let totalItems = 0;
    cart.forEach((item, index) => {
      totalItems += item.quantity;
      const li = document.createElement('li');
      li.className = 'mini-cart-item';
      li.innerHTML = `
        <img src="${item.imageUrl || 'assets/images/placeholder.png'}" alt="${item.name}" style="width:48px;height:48px;object-fit:cover;border-radius:6px;margin-right:8px;">
        <div style="display:inline-block;vertical-align:top;">
          <span class="cart-item-name" style="font-size:1em;font-weight:500;">${item.name}</span><br>
          <span class="cart-item-price" style="color:#444;">$${(item.price * item.quantity).toLocaleString('es-CO')}</span>
          <span class="cart-item-qty" style="color:#888;"> x${item.quantity}</span>
        </div>
        <button class="mini-cart-remove-btn" title="Eliminar" onclick="removeItem(${index})" style="background:none;border:none;color:#c0392b;font-size:1.3em;float:right;cursor:pointer;">❌</button>
      `;
      miniCartList.appendChild(li);
    });

    // ACTUALIZA EL CONTADOR AQUÍ, después de calcular totalItems
    const cartCountDesktop = document.getElementById('cart-count-desktop');
    const cartCountMobile = document.getElementById('cart-count-mobile');
    if (cartCountDesktop) cartCountDesktop.textContent = totalItems;
    if (cartCountMobile) cartCountMobile.textContent = totalItems;

    // Total en mini-carrito
    const totalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const totalDiv = document.createElement('div');
    totalDiv.classList.add('cart-total');
    totalDiv.innerHTML = `<strong>Total: $${totalPrice.toLocaleString('es-CO')}</strong>`;
    miniCartList.appendChild(totalDiv);
  }

  // Carrito grande (carrito.php)
  const cartTableBody = document.getElementById('cart-items-table');
  if (cartTableBody) {
    cartTableBody.innerHTML = '';
    if (cart.length === 0) {
      cartTableBody.innerHTML = `<tr><td colspan="5"><div class="cart-empty-message">No tienes productos en el carrito.</div></td></tr>`;
      document.getElementById('cart-total').style.display = 'none';
      return;
    } else {
      document.getElementById('cart-total').style.display = '';
    }
    cart.forEach((item, index) => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><img src="${item.imageUrl || 'assets/images/placeholder.png'}" alt="${item.name}" class="cart-item-img" style="width:90px;height:90px;object-fit:cover;border-radius:8px;"></td>
        <td style="font-weight:500;">${item.name}</td>
        <td style="font-weight:600;">$${(item.price * item.quantity).toLocaleString('es-CO')}</td>
        <td>
          <div style="display:flex;align-items:center;justify-content:center;gap:8px;">
            <button class="cart-btn" onclick="decreaseQuantity(${index})" title="Restar">-</button>
            <input type="number" min="1" value="${item.quantity}" 
              onchange="actualizarCantidad(${index}, this.value)" 
              onblur="actualizarCantidad(${index}, this.value)"
              onkeydown="if(event.key==='Enter'){actualizarCantidad(${index}, this.value)}" style="width:50px;">
            <button class="cart-btn" onclick="increaseQuantity(${index})" title="Sumar">+</button>
          </div>
        </td>
        <td> 
          <button class="cart-btn" onclick="removeItem(${index})" title="Eliminar" style="color:#c0392b;font-size:1.5em;">❌</button>
        </td>
      `;
      cartTableBody.appendChild(tr);
    });
    // Total en carrito grande
    const totalPrice = cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
    const totalSpan = document.getElementById('total-amount');
    if (totalSpan) {
      totalSpan.textContent = totalPrice.toLocaleString('es-CO');
    }
  }
}

  
function increaseQuantity(index) {
  cart[index].quantity++;
  saveCart();
  updateCartDisplay();
}

function decreaseQuantity(index) {
  if (cart[index].quantity > 1) {
    cart[index].quantity--;
  } else {
    cart.splice(index, 1);
  }
  saveCart();
  updateCartDisplay();
}

function removeItem(index) {
  cart.splice(index, 1);
  saveCart();
  updateCartDisplay();
}

function clearCart() {
  cart = [];
  saveCart();
  updateCartDisplay();
}

function saveCart() {
  localStorage.setItem('cart', JSON.stringify(cart));
}

function loadCart() {
  const storedCart = localStorage.getItem('cart');
  if (storedCart) {
    cart = JSON.parse(storedCart);
  }
}

function updateTotal() {
  const items = JSON.parse(localStorage.getItem("cart")) || [];
  let total = 0;

  items.forEach(item => {
    total += item.price * item.quantity;
  });

  const totalElement = document.getElementById("total-amount");
  if (totalElement) {
    totalElement.textContent = total.toFixed(2);
  }
}

// NUEVO: Actualiza el contador del carrito en el icono
function actualizarContadorCarrito() {
  let cart = JSON.parse(localStorage.getItem('cart')) || [];
  let total = cart.reduce((sum, item) => sum + item.quantity, 0);
  const cartCountDesktop = document.getElementById('cart-count-desktop');
  const cartCountMobile = document.getElementById('cart-count-mobile');
  if (cartCountDesktop) cartCountDesktop.textContent = total;
  if (cartCountMobile) cartCountMobile.textContent = total;
}

// NUEVO: Función para validar stock antes de comprar
function validarStockAntesDeComprar(callback) {
  fetch('envio/validar_stock_tienda.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify(cart)
  })
  .then(res => res.json())
  .then(data => {
    if (data.sin_stock && data.sin_stock.length > 0) {
      let mensaje = 'No hay suficiente stock para:\n';
      data.sin_stock.forEach(item => {
        mensaje += `- ${item.producto} (${item.color}, ${item.talla}): solo hay ${item.stock_disponible}\n`;
      });
      Swal.fire('Stock insuficiente', mensaje, 'warning');
      callback(false);
    } else {
      callback(true);
    }
  })
  .catch(() => {
    Swal.fire('Error', 'No se pudo validar el stock. Intenta de nuevo.', 'error');
    callback(false);
  });
}

// NUEVO: Función para actualizar la cantidad de un producto en el carrito
function actualizarCantidad(index, nuevaCantidad) {
  nuevaCantidad = parseInt(nuevaCantidad);
  if (isNaN(nuevaCantidad) || nuevaCantidad < 1) return;
  const item = cart[index];
  // Validar stock en la base de datos antes de actualizar
  fetch('envio/validar_stock_tienda.php', {
    method: 'POST',
    headers: {'Content-Type': 'application/json'},
    body: JSON.stringify([{...item, quantity: nuevaCantidad}])
  })
  .then(res => res.json())
  .then(data => {
    if (data.sin_stock && data.sin_stock.length > 0) {
      Swal.fire(
        'Stock insuficiente',
        `Solo hay ${data.sin_stock[0].stock_disponible} unidades disponibles de ${item.name} (${item.color}, ${item.talla})`,
        'warning'
      );
      // Opcional: poner la cantidad máxima disponible en el input
      cart[index].quantity = data.sin_stock[0].stock_disponible > 0 ? data.sin_stock[0].stock_disponible : 1;
      saveCart();
      updateCartDisplay();
    } else {
      cart[index].quantity = nuevaCantidad;
      saveCart();
      updateCartDisplay();
    }
  });
}

// Inicialización
document.addEventListener('DOMContentLoaded', actualizarContadorCarrito);