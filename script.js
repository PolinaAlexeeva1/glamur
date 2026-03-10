// Функционал корзины
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartDisplay();
    updateCartCounter();
}

function updateCartCounter() {
    const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
    document.getElementById('cart-count').textContent = totalItems;
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const cartTable = document.getElementById('cart-table');
    const emptyCart = document.getElementById('empty-cart');
    const cartActions = document.getElementById('cart-actions');
    
    if (cart.length === 0) {
        cartTable.style.display = 'none';
        emptyCart.style.display = 'block';
        cartActions.style.display = 'none';
        return;
    }
    
    cartTable.style.display = 'table';
    emptyCart.style.display = 'none';
    cartActions.style.display = 'block';
    
    cartItems.innerHTML = cart.map((item, index) => `
        <tr>
            <td><img src="img/${item.id}.jpg" alt="${item.name}" class="cart-image"></td>
            <td>${item.name}</td>
            <td>${item.price} ₽</td>
            <td>
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="changeQuantity(${index}, -1)">-</button>
                    <input type="number" class="quantity-input" value="${item.quantity}" min="1" onchange="updateQuantity(${index}, this.value)">
                    <button class="quantity-btn" onclick="changeQuantity(${index}, 1)">+</button>
                </div>
            </td>
            <td>${(item.price * item.quantity).toLocaleString()} ₽</td>
            <td><button class="remove-btn" onclick="removeFromCart(${index})">Удалить</button></td>
        </tr>
    `).join('');
    
    updateTotal();
}

function updateTotal() {
    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    document.getElementById('cart-total').textContent = total.toLocaleString() + ' ₽';
}

function addToCart(product) {
    const existingItem = cart.find(item => item.id == product.dataset.id);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            id: product.dataset.id,
            name: product.dataset.name,
            price: parseInt(product.dataset.price),
            quantity: 1
        });
    }
    
    saveCart();
}

function changeQuantity(index, delta) {
    cart[index].quantity += delta;
    if (cart[index].quantity < 1) {
        cart[index].quantity = 1;
    }
    saveCart();
}

function updateQuantity(index, quantity) {
    const qty = parseInt(quantity);
    if (qty < 1) {
        cart[index].quantity = 1;
    } else {
        cart[index].quantity = qty;
    }
    saveCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    saveCart();
}

// Инициализация
document.addEventListener('DOMContentLoaded', function() {
    updateCartDisplay();
    updateCartCounter();
    
    // Обработчики кнопок "В корзину"
    document.querySelectorAll('.add-to-cart').forEach(button => {
        button.addEventListener('click', function() {
            addToCart(this.parentElement);
        });
    });
    
    // Обработчик оформления заказа
    document.querySelector('.checkout-btn')?.addEventListener('click', function() {
        if (cart.length > 0) {
            alert('Переход к оформлению заказа...');
            // Здесь можно добавить редирект на страницу оформления
        }
    });
});
