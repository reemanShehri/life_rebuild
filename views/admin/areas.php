<?php
// المتغيرات المتاحة: $areas, $governorates, $totalPages, $page, $search
?>
<style>
    /* تنسيق قوي لصفحة المناطق */
    .admin-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }
    .search-form {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .search-form input {
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        border: 1px solid #cbd5e1;
        font-family: inherit;
    }
    .search-form button, .reset-btn {
        background: #1e3a8a;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        cursor: pointer;
        text-decoration: none;
        font-family: inherit;
    }
    .reset-btn {
        background: #64748b;
    }
    .add-area-btn {
        margin-bottom: 1rem;
    }
    .btn-add-area {
        background: #10b981;
        color: white;
        border: none;
        padding: 0.6rem 1.2rem;
        border-radius: 2rem;
        cursor: pointer;
        font-weight: bold;
    }
    .form-card {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .form-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1rem;
    }
    .form-group {
        flex: 1;
        min-width: 150px;
    }
    .form-group label {
        display: block;
        font-weight: 600;
        margin-bottom: 0.3rem;
    }
    .form-group input, .form-group select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
    }
    .btn-save {
        background: #10b981;
        color: white;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 2rem;
        cursor: pointer;
        margin-left: 0.5rem;
    }
    .btn-cancel {
        background: #e2e8f0;
        border: none;
        padding: 0.5rem 1.2rem;
        border-radius: 2rem;
        cursor: pointer;
    }
    .data-table-wrapper {
        overflow-x: auto;
        margin-top: 1rem;
    }
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
        border-radius: 0.5rem;
    }
    .admin-table th, .admin-table td {
        border: 1px solid #e2e8f0;
        padding: 0.6rem;
        text-align: right;
        vertical-align: middle;
    }
    .admin-table th {
        background: #f1f5f9;
        font-weight: 700;
        color: #0f172a;
    }
    .admin-table td {
        background: white;
        color: #1e293b;
    }
    .actions {
        display: flex;
        gap: 0.3rem;
        flex-wrap: wrap;
    }
    .edit-btn, .delete-btn {
        background: #e2e8f0;
        border: none;
        padding: 0.3rem 0.8rem;
        border-radius: 2rem;
        cursor: pointer;
        font-family: inherit;
    }
    .delete-btn:hover {
        background: #fee2e2;
    }
    .pagination {
        margin-top: 1.5rem;
        display: flex;
        gap: 0.3rem;
        justify-content: center;
    }
    .pagination a {
        padding: 0.3rem 0.7rem;
        border: 1px solid #cbd5e1;
        border-radius: 0.5rem;
        text-decoration: none;
        color: #1e293b;
    }
    .pagination a.active {
        background: #1e3a8a;
        color: white;
    }
    .modal {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
    }
    .modal-content {
        background: white;
        padding: 1.5rem;
        border-radius: 1rem;
        width: 500px;
        max-width: 90%;
    }
    .close {
        float: left;
        cursor: pointer;
        font-size: 1.5rem;
        font-weight: bold;
    }
    @media (max-width: 700px) {
        .admin-section-header {
            flex-direction: column;
            align-items: stretch;
            gap: 0.5rem;
        }
        .search-form {
            justify-content: flex-start;
        }
    }
</style>

<div class="admin-section-header">
    <h2>🗺️ إدارة المناطق</h2>
    <form method="GET" class="search-form">
        <input type="hidden" name="page" value="admin">
        <input type="hidden" name="tab" value="areas">
        <input type="text" name="search" placeholder="🔍 بحث باسم المنطقة..." value="<?= htmlspecialchars($search ?? '') ?>">
        <button type="submit">بحث</button>
        <a href="index.php?page=admin&tab=areas" class="reset-btn">إعادة تعيين</a>
    </form>
</div>

<div class="add-area-btn">
    <button class="btn-add-area" onclick="toggleAddForm()">➕ إضافة منطقة جديدة</button>
</div>

<div id="addAreaForm" class="form-card" style="display: none;">
    <h3>إضافة منطقة</h3>
    <form method="POST">
        <input type="hidden" name="add_area" value="1">
        <div class="form-row">
            <div class="form-group">
                <label>المحافظة</label>
                <select name="governorate_id" required>
                    <option value="">اختر المحافظة</option>
                    <?php foreach ($governorates as $gov): ?>
                        <option value="<?= $gov['id'] ?>"><?= htmlspecialchars($gov['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>اسم المنطقة</label>
                <input type="text" name="area_name" required>
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label><input type="checkbox" name="is_safe" value="1"> آمنة</label></div>
            <div class="form-group"><label><input type="checkbox" name="has_water" value="1"> مياه</label></div>
            <div class="form-group"><label><input type="checkbox" name="has_electricity" value="1"> كهرباء</label></div>
            <div class="form-group"><label><input type="checkbox" name="has_health_center" value="1"> مركز صحي</label></div>
            <div class="form-group"><label><input type="checkbox" name="has_school" value="1"> مدرسة</label></div>
            <div class="form-group"><label><input type="checkbox" name="needs_reconstruction" value="1"> بحاجة إعمار</label></div>
        </div>
        <div class="form-group">
            <label>مستوى الأمان</label>
            <select name="safety_level">
                <option value="high">مرتفع</option>
                <option value="medium">متوسط</option>
                <option value="low">منخفض</option>
            </select>
        </div>
        <button type="submit" class="btn-save">💾 حفظ</button>
        <button type="button" class="btn-cancel" onclick="toggleAddForm()">إلغاء</button>
    </form>
</div>

<div class="data-table-wrapper">
    <table class="admin-table">
        <thead>
            <tr>
                <th>المحافظة</th>
                <th>اسم المنطقة</th>
                <th>آمنة</th>
                <th>مياه</th>
                <th>كهرباء</th>
                <th>مركز صحي</th>
                <th>مدرسة</th>
                <th>بحاجة إعمار</th>
                <th>مستوى الأمان</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($areas) > 0): ?>
                <?php foreach ($areas as $area): ?>
                <tr>
                    <td><?= htmlspecialchars($area['governorate_name'] ?? '') ?></td>
                    <td><?= htmlspecialchars($area['area_name']) ?></td>
                    <td><?= $area['is_safe'] ? '✅' : '❌' ?></td>
                    <td><?= $area['has_water'] ? '✅' : '❌' ?></td>
                    <td><?= $area['has_electricity'] ? '✅' : '❌' ?></td>
                    <td><?= $area['has_health_center'] ? '✅' : '❌' ?></td>
                    <td><?= $area['has_school'] ? '✅' : '❌' ?></td>
                    <td><?= $area['needs_reconstruction'] ? '✅' : '❌' ?></td>
                    <td><?= $area['safety_level'] == 'high' ? 'مرتفع' : ($area['safety_level'] == 'medium' ? 'متوسط' : 'منخفض') ?></td>
                    <td class="actions">
                        <button class="edit-btn" onclick="openEditModal(<?= $area['id'] ?>, '<?= addslashes(htmlspecialchars($area['area_name'])) ?>', <?= $area['governorate_id'] ?>, <?= $area['is_safe'] ?>, <?= $area['has_water'] ?>, <?= $area['has_electricity'] ?>, <?= $area['has_health_center'] ?>, <?= $area['has_school'] ?>, <?= $area['needs_reconstruction'] ?>, '<?= $area['safety_level'] ?>')">✏️ تعديل</button>
                        <form method="POST" style="display:inline;" onsubmit="return confirm('هل أنت متأكد من حذف هذه المنطقة؟')">
                            <input type="hidden" name="delete_area" value="1">
                            <input type="hidden" name="area_id" value="<?= $area['id'] ?>">
                            <button type="submit" class="delete-btn">🗑️ حذف</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="10" style="text-align:center;">لا توجد مناطق مطابقة للبحث</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if ($totalPages > 1): ?>
<div class="pagination">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="index.php?page=admin&tab=areas&p=<?= $i ?>&search=<?= urlencode($search ?? '') ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
    <?php endfor; ?>
</div>
<?php endif; ?>

<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeEditModal()">&times;</span>
        <h3>تعديل المنطقة</h3>
        <form method="POST">
            <input type="hidden" name="update_area" value="1">
            <input type="hidden" name="area_id" id="edit_area_id">
            <div class="form-group">
                <label>المحافظة</label>
                <select name="governorate_id" id="edit_governorate_id" required>
                    <?php foreach ($governorates as $gov): ?>
                        <option value="<?= $gov['id'] ?>"><?= htmlspecialchars($gov['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>اسم المنطقة</label>
                <input type="text" name="area_name" id="edit_area_name" required>
            </div>
            <div class="form-row">
                <label><input type="checkbox" name="is_safe" id="edit_is_safe" value="1"> آمنة</label>
                <label><input type="checkbox" name="has_water" id="edit_has_water" value="1"> مياه</label>
                <label><input type="checkbox" name="has_electricity" id="edit_has_electricity" value="1"> كهرباء</label>
                <label><input type="checkbox" name="has_health_center" id="edit_has_health_center" value="1"> مركز صحي</label>
                <label><input type="checkbox" name="has_school" id="edit_has_school" value="1"> مدرسة</label>
                <label><input type="checkbox" name="needs_reconstruction" id="edit_needs_reconstruction" value="1"> بحاجة إعمار</label>
            </div>
            <div class="form-group">
                <label>مستوى الأمان</label>
                <select name="safety_level" id="edit_safety_level">
                    <option value="high">مرتفع</option>
                    <option value="medium">متوسط</option>
                    <option value="low">منخفض</option>
                </select>
            </div>
            <button type="submit" class="btn-save">💾 حفظ التغييرات</button>
            <button type="button" class="btn-cancel" onclick="closeEditModal()">إلغاء</button>
        </form>
    </div>
</div>

<script>
    function toggleAddForm() {
        var form = document.getElementById('addAreaForm');
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
        } else {
            form.style.display = 'none';
        }
    }
    function openEditModal(id, name, govId, isSafe, hasWater, hasElectricity, hasHealth, hasSchool, needsRecon, safety) {
        document.getElementById('edit_area_id').value = id;
        document.getElementById('edit_area_name').value = name;
        document.getElementById('edit_governorate_id').value = govId;
        document.getElementById('edit_is_safe').checked = isSafe == 1;
        document.getElementById('edit_has_water').checked = hasWater == 1;
        document.getElementById('edit_has_electricity').checked = hasElectricity == 1;
        document.getElementById('edit_has_health_center').checked = hasHealth == 1;
        document.getElementById('edit_has_school').checked = hasSchool == 1;
        document.getElementById('edit_needs_reconstruction').checked = needsRecon == 1;
        document.getElementById('edit_safety_level').value = safety;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeEditModal() {
        document.getElementById('editModal').style.display = 'none';
    }
</script>
