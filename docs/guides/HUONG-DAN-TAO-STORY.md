# HƯỚNG DẪN TẠO STORY - BMAD METHOD

## 🎯 QUY TRÌNH ĐƠN GIẢN

### BƯỚC 1: SPRINT PLANNING (Chỉ làm 1 lần đầu)

**Agent:** Scrum Master (Bob)

**Lệnh:**

```
.bmad/bmm/agents/sm.md
*sprint-planning
```

**Kết quả:** Tạo file `docs/3-implementation/sprint-status.yaml` với 47 stories

---

### BƯỚC 2: TẠO STORY (Lặp lại cho mỗi story)

**Agent:** Scrum Master (Bob)

**Lệnh:**

```
.bmad/bmm/agents/sm.md
*create-story
```

**Kết quả:**

-   Tạo file story: `docs/3-implementation/X-Y-story-name.md`
-   Update status: `backlog` → `ready-for-dev`

---

### BƯỚC 3: VALIDATE STORY (Tùy chọn)

**Agent:** Scrum Master (Bob)

**Lệnh:**

```
.bmad/bmm/agents/sm.md
*validate-create-story
```

**Kết quả:** Review và cải thiện story

---

### BƯỚC 4: IMPLEMENT STORY

**Agent:** Developer (Dev)

**Lệnh:**

```
.bmad/bmm/agents/dev.md
*dev-story
```

**Kết quả:** Code được implement theo story

---

### BƯỚC 5: CODE REVIEW

**Agent:** Developer (Dev)

**Lệnh:**

```
.bmad/bmm/agents/dev.md
*code-review
```

**Kết quả:** Story được mark `done`

---

## 📋 DANH SÁCH AGENTS VÀ LỆNH

### 1. SCRUM MASTER (Bob) - `.bmad/bmm/agents/sm.md`

**Lệnh chính:**

-   `*sprint-planning` - Tạo sprint-status.yaml (1 lần duy nhất)
-   `*create-story` - Tạo story tiếp theo
-   `*validate-create-story` - Review story
-   `*epic-retrospective` - Retrospective sau khi hoàn thành epic
-   `*correct-course` - Điều chỉnh khi off-track

### 2. DEVELOPER (Dev) - `.bmad/bmm/agents/dev.md`

**Lệnh chính:**

-   `*dev-story` - Implement story
-   `*code-review` - Review code và mark done
-   `*fix-bug` - Fix bug
-   `*refactor` - Refactor code

### 3. PRODUCT MANAGER (PM) - `.bmad/bmm/agents/pm.md`

**Lệnh chính:**

-   `*create-epic` - Tạo epic mới
-   `*update-epic` - Update epic
-   `*add-story` - Thêm story vào epic

---

## 🔄 QUY TRÌNH LẶP LẠI

**Để tạo tất cả 47 stories:**

1. Chạy 1 lần: `.bmad/bmm/agents/sm.md` → `*sprint-planning`

2. Lặp lại 47 lần:

    ```
    .bmad/bmm/agents/sm.md
    *create-story
    ```

3. Implement từng story:

    ```
    .bmad/bmm/agents/dev.md
    *dev-story
    ```

4. Review từng story:
    ```
    .bmad/bmm/agents/dev.md
    *code-review
    ```

---

## 📊 TRACKING

**Xem tiến độ:** Mở file `docs/3-implementation/sprint-status.yaml`

**Story Status:**

-   `backlog` - Chưa tạo story file
-   `ready-for-dev` - Story đã tạo, sẵn sàng code
-   `in-progress` - Đang code
-   `review` - Đang review
-   `done` - Hoàn thành

---

## ⚡ QUICK REFERENCE

| Công việc       | Agent | Lệnh                     |
| --------------- | ----- | ------------------------ |
| Khởi tạo sprint | SM    | `*sprint-planning`       |
| Tạo story       | SM    | `*create-story`          |
| Validate story  | SM    | `*validate-create-story` |
| Code story      | Dev   | `*dev-story`             |
| Review code     | Dev   | `*code-review`           |
| Retrospective   | SM    | `*epic-retrospective`    |

---

**Lưu ý:** Tất cả lệnh đều chạy trong YOLO mode (tự động, không cần confirm từng bước)
