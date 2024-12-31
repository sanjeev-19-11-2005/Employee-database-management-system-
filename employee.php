<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Page - Employee Portal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    /* General Styles */
    body {
      font-family: Arial, sans-serif;
      background-color: #e6e0ff;
      margin: 0;
      padding: 0;
      color: #4b2c5e;
    }

    .admin-header {
      background-color: #673ab7;
      color: white;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .navigation {
      background-color: #f3e5f5;
    }

    .navigation button {
      color: #673ab7;
      border-color: #673ab7;
    }

    .navigation button:hover {
      background-color: #673ab7;
      color: white;
    }

    .table-section {
      padding: 20px;
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .table-striped tbody tr:nth-of-type(odd) {
      background-color: #f3e5f5;
    }

    .modal-content {
      border-radius: 8px;
      background-color: #f3e5f5;
      color: #4b2c5e;
    }

    .modal-header {
      background-color: #673ab7;
      color: white;
    }
  </style>
</head>
<body>
  <header class="admin-header bg-dark text-white py-3 px-4 d-flex justify-content-between align-items-center shadow">
    <div class="d-flex align-items-center">
      <img src="admin-avatar.jpg" alt="Admin Avatar" class="rounded-circle me-2" width="50" height="50">
      <h1 class="h5 mb-0">Welcome Admin</h1>
    </div>
    <button class="btn btn-outline-light btn-sm" onclick="window.location.href='logout.php'">Logout</button>
  </header>

  <nav class="navigation bg-light py-3 shadow-sm d-flex justify-content-center">
    <button class="btn btn-outline-dark mx-2" id="employee-btn">Manage Employees</button>
  </nav>

  <main class="container my-5">
    <section id="employee-section" class="table-section">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Employee Management</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add Employee</button>
      </div>
      <div class="table-responsive shadow-sm rounded">
        <table class="table table-striped table-hover align-middle">
          <thead class="table-dark">
            <tr>
              <th>ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="employeeTable">
            <!-- Employee rows will be added here -->
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <!-- Add Employee Modal -->
  <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addEmployeeModalLabel">Add New Employee</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="employeeForm">
            <div class="mb-3">
              <label for="employeeName" class="form-label">Name</label>
              <input type="text" class="form-control" id="employeeName" required>
            </div>
            <div class="mb-3">
              <label for="employeeEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="employeeEmail" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="employeeForm">Save Employee</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Edit Employee Modal -->
  <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Employee Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form id="editForm">
            <input type="hidden" id="editId">
            <div class="mb-3">
              <label for="editName" class="form-label">Name</label>
              <input type="text" class="form-control" id="editName" required>
            </div>
            <div class="mb-3">
              <label for="editEmail" class="form-label">Email</label>
              <input type="email" class="form-control" id="editEmail" required>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary" form="editForm">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
  <script>


document.getElementById('employeeForm').addEventListener('submit', function (e) {
  e.preventDefault(); // Prevent form default submission

  const name = document.getElementById('employeeName').value.trim();
  const email = document.getElementById('employeeEmail').value.trim();

  if (!name || !email) {
    alert('Please fill in both name and email');
    return;
  }

  fetch('save_employee.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`,
  })
    .then((response) => response.json())
    .then((data) => {
      console.log('Response from server:', data); // Debugging log
      if (data.status === 'success') {
        alert(data.message);

        // Add the new employee to the table dynamically
        const newRow = document.createElement('tr');
        newRow.setAttribute('data-id', data.id); // Assuming the response contains the ID
        newRow.innerHTML = `
          <td>${data.id}</td>
          <td>${name}</td>
          <td>${email}</td>
          <td>
            <button class="btn btn-outline-primary btn-sm edit-btn">Edit</button>
            <button class="btn btn-outline-danger btn-sm remove-btn">Remove</button>
          </td>
        `;
        document.getElementById('employeeTable').appendChild(newRow);

        // Reset the form
        document.getElementById('employeeForm').reset();

        // Close the modal
        bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal')).hide();
      } else {
        alert(data.message);
      }
    })
    .catch((error) => {
      console.error('Error:', error); // Debugging log
    });
});





    function fetchEmployees() {
      fetch('fetch_employees.php')
        .then(response => response.json())
        .then(data => {
          const employeeTable = document.getElementById('employeeTable');
          employeeTable.innerHTML = '';
          data.forEach(employee => {
            const row = `
              <tr data-id="${employee.id}">
                <td>${employee.id}</td>
                <td>${employee.name}</td>
                <td>${employee.email}</td>
                <td>
                  <button class="btn btn-outline-primary btn-sm edit-btn">Edit</button>
                  <button class="btn btn-outline-danger btn-sm remove-btn">Remove</button>
                </td>
              </tr>`;
            employeeTable.innerHTML += row;
          });
        });
    }

    document.getElementById('employeeForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const name = document.getElementById('employeeName').value.trim();
      const email = document.getElementById('employeeEmail').value.trim();

      if (!name || !email) {
        alert('Please fill in both name and email');
        return;
      }

      fetch('save_employee.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`
      })
        .then(response => response.json())
        .then(data => {
          alert(data.message);
          if (data.status === 'success') {
            fetchEmployees();
            document.getElementById('employeeForm').reset();
            bootstrap.Modal.getInstance(document.getElementById('addEmployeeModal')).hide();
          }
        });
    });

    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('edit-btn')) {
        const row = e.target.closest('tr');
        const id = row.dataset.id;
        const name = row.children[1].textContent;
        const email = row.children[2].textContent;

        document.getElementById('editId').value = id;
        document.getElementById('editName').value = name;
        document.getElementById('editEmail').value = email;

        const editModal = new bootstrap.Modal(document.getElementById('editModal'));
        editModal.show();
      }
    });

    document.getElementById('editForm').addEventListener('submit', function(e) {
      e.preventDefault();
      const id = document.getElementById('editId').value;
      const name = document.getElementById('editName').value.trim();
      const email = document.getElementById('editEmail').value.trim();

      if (!name || !email) {
        alert('Please fill in both name and email');
        return;
      }

      fetch('update_employee.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&name=${encodeURIComponent(name)}&email=${encodeURIComponent(email)}`
      })
        .then(response => response.json())
        .then(data => {
          alert(data.message);
          if (data.status === 'success') {
            fetchEmployees();
            bootstrap.Modal.getInstance(document.getElementById('editModal')).hide();
          }
        });
    });

    document.addEventListener('click', function(e) {
      if (e.target.classList.contains('remove-btn')) {
        const row = e.target.closest('tr');
        const id = row.dataset.id;

        if (confirm('Are you sure you want to delete this employee?')) {
          fetch('delete_employee.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: `id=${id}`
          })
            .then(response => response.json())
            .then(data => {
              alert(data.message);
              if (data.status === 'success') {
                fetchEmployees();
              }
            });
        }
      }
    });

    fetchEmployees();
  </script>
</body>
</html>
