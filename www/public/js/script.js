function getProducts() {
  let products = JSON.parse(localStorage.getItem('products'));
  if (!products || !Array.isArray(products)) products = [];
  return products;
}

function saveProducts(products) {
  localStorage.setItem('products', JSON.stringify(products));
}

if (document.getElementById('product_list_form')) {
  const addProductBtn = document.getElementById('add-product-btn');
  const productGrid = document.getElementById('product-grid');
  const productForm = document.getElementById('product_list_form');

  addProductBtn.addEventListener('click', () => {
    window.location.href = 'add-product.html';
  });

  function createProductHTML(product) {
    let spec = '';
    if (product.type === 'DVD') spec = `Size: ${product.size}`;
    else if (product.type === 'Book') spec = `Weight: ${product.weight}`;
    else if (product.type === 'Furniture') spec = `Dimension: ${product.dimensions}`;

    return `
      <div class="product-item">
        <input type="checkbox" class="delete-checkbox" name="product[]" value="${product.sku}">
        <div>${product.sku}</div>
        <div>${product.name}</div>
        <div>${parseFloat(product.price).toFixed(2)} $</div>
        <div>${spec}</div>
      </div>
    `;
  }

  function loadProducts() {
    const products = getProducts();
    productGrid.innerHTML = products.map(createProductHTML).join('');
  }

  productForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const checkboxes = document.querySelectorAll('.delete-checkbox:checked');
    const toDelete = Array.from(checkboxes).map(cb => cb.value);
    if (toDelete.length === 0) return alert('Select products to delete.');

    let products = getProducts();
    products = products.filter(p => !toDelete.includes(p.sku));
    saveProducts(products);
    loadProducts();
  });

  loadProducts();
}

if (document.getElementById('product_form')) {
  const productForm = document.getElementById('product_form');
  const productType = document.getElementById('productType');
  const typeFields = document.getElementById('type-fields');

  productType.addEventListener('change', () => {
    let html = '';
    if (productType.value === 'DVD') {
      html = `
        <label for="size">Size (MB)</label>
        <input type="number" id="size" name="size" min="0" required />
        <small>Please provide size in MB.</small>
      `;
    } else if (productType.value === 'Furniture') {
      html = `
        <label for="height">Height (CM)</label>
        <input type="number" id="height" name="height" min="0" required />

        <label for="width">Width (CM)</label>
        <input type="number" id="width" name="width" min="0" required />

        <label for="length">Length (CM)</label>
        <input type="number" id="length" name="length" min="0" required />
        <small>Please provide dimensions in HxWxL format.</small>
      `;
    } else if (productType.value === 'Book') {
      html = `
        <label for="weight">Weight (KG)</label>
        <input type="number" id="weight" name="weight" step="0.01" min="0" required />
        <small>Please provide weight in KG.</small>
      `;
    }
    typeFields.innerHTML = html;
  });

  productForm.addEventListener('submit', (e) => {
    e.preventDefault();
    const sku = document.getElementById('sku').value.trim();
    const name = document.getElementById('name').value.trim();
    const price = parseFloat(document.getElementById('price').value).toFixed(2);
    const type = productType.value;
    const products = getProducts();

    if (products.some(p => p.sku.toLowerCase() === sku.toLowerCase())) {
      alert('SKU must be unique.');
      return;
    }

    let productData = { sku, name, price, type };

    if (type === 'DVD') {
      const size = document.getElementById('size').value;
      if (!size) return alert('Enter size.');
      productData.size = size + ' MB';
    } else if (type === 'Furniture') {
      const h = document.getElementById('height').value;
      const w = document.getElementById('width').value;
      const l = document.getElementById('length').value;
      if (!h || !w || !l) return alert('Fill all dimensions.');
      productData.dimensions = `${h}x${w}x${l}`;
    } else if (type === 'Book') {
      const weight = document.getElementById('weight').value;
      if (!weight) return alert('Enter weight.');
      productData.weight = weight + ' KG';
    }

    products.push(productData);
    saveProducts(products);
    window.location.href = 'index.html';
  });
}
