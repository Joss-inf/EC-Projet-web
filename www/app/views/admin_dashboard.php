<form action="./backend/controllers/upload_files.php" method="post" enctype="multipart/form-data">
<label>Sélectionner des fichiers CSV (csv conforme a  https://www.data.gouv.fr/fr/datasets/pollution-suivi-des-flux-industriels/) :</label>
<input type="file" name="csvfiles[]" accept=".csv" multiple required>
<button type="submit">Envoyer</button>
</form>