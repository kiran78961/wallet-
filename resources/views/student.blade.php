<!DOCTYPE html>
<html>
<head>
    <title>Students</title>

    <style>
        table {
            width: 80%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
        }
    </style>
</head>

<body>

<h2>Student List</h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
        </tr>
    </thead>

    <tbody id="studentData">
        <tr>
            <td colspan="4">Loading...</td>
        </tr>
    </tbody>
</table>

<script>

fetch('/api/students')
    .then(response => response.json())
    .then(result => {

        console.log(result);

        let students = result.data;

        let html = '';

        students.forEach(student => {

            html += `
                <tr>
                    <td>${student.id}</td>
                    <td>${student.name}</td>
                    <td>${student.email}</td>
                    <td>${student.phone}</td>
                </tr>
            `;

        });

        document.getElementById('studentData').innerHTML = html;

    })
    .catch(error => {

        console.log(error);

    });

</script>

</body>
</html>