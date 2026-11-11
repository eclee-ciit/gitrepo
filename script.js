let records = JSON.parse(localStorage.getItem('crudRecords')) || [];

const form = document.getElementById('crud-form');
const idInput = document.getElementById('record-id');
const nameInput = document.getElementById('name-input');
const roleInput = document.getElementById('role-input');
const submitBtn = document.getElementById('submit-btn');
const cancelBtn = document.getElementById('cancel-btn');
const tableBody = document.getElementById('table-body');

// READ: Render records to the table
function renderTable() {
  tableBody.innerHTML = '';
  records.forEach((record) => {
    const row = document.createElement('tr');
    row.innerHTML = `
      <td>${record.name}</td>
      <td>${record.role}</td>
      <td>
        <button class="edit-btn" onclick="editRecord('${record.id}')">Edit</button>
        <button class="delete-btn" onclick="deleteRecord('${record.id}')">Delete</button>
      </td>
    `;
    tableBody.appendChild(row);
  });
}

// Save to browser storage
function saveAndRender() {
  localStorage.setItem('crudRecords', JSON.stringify(records));
  renderTable();
}

// CREATE & UPDATE: Handle form submission
form.addEventListener('submit', (e) => {
  e.preventDefault();
  const id = idInput.value;
  const name = nameInput.value.trim();
  const role = roleInput.value.trim();

  if (id) {
    // Update existing record
    const index = records.findIndex((item) => item.id === id);
    if (index !== -1) {
      records[index] = { id, name, role };
    }
  } else {
    // Create new record
    const newRecord = {
      id: Date.now().toString(),
      name,
      role
    };
    records.push(newRecord);
  }

  resetForm();
  saveAndRender();
});

// Populate form for editing
window.editRecord = function(id) {
  const record = records.find((item) => item.id === id);
  if (!record) return;

  idInput.value = record.id;
  nameInput.value = record.name;
  roleInput.value = record.role;
  submitBtn.textContent = 'Update Item';
  cancelBtn.classList.remove('hidden');
};

// DELETE: Remove record
window.deleteRecord = function(id) {
  records = records.filter((item) => item.id !== id);
  if (idInput.value === id) resetForm();
  saveAndRender();
};

// Reset form state
function resetForm() {
  idInput.value = '';
  form.reset();
  submitBtn.textContent = 'Add Item';
  cancelBtn.classList.add('hidden');
}

cancelBtn.addEventListener('click', resetForm);

// Initial load
renderTable();
