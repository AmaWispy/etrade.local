const fs = require('fs');
const path = require('path');

const inputPath = path.join(__dirname, 'Untitled-2.json');
const outputPath = path.join(__dirname, 'parents-result.txt');

try {
    // Читаем JSON файл
    const data = JSON.parse(fs.readFileSync(inputPath, 'utf8'));

    // Создаем Map для хранения уникальных значений Parent и их количества
    const parentsMap = new Map();

    // Собираем все уникальные значения Parent и считаем их количество
    data.forEach(item => {
        if (item.Parent) {
            parentsMap.set(item.Parent, (parentsMap.get(item.Parent) || 0) + 1);
        }
    });

    // Преобразуем Map в массив и сортируем по имени
    const sortedParents = Array.from(parentsMap.entries()).sort((a, b) => a[0].localeCompare(b[0]));

    // Формируем результат
    let result = `\nВсего уникальных Parent: ${parentsMap.size}\n\n`;
    result += 'Список всех уникальных Parent и их количество:\n';
    result += '-------------------------------------------\n';
    sortedParents.forEach(([parent, count]) => {
        result += `${parent}: ${count}\n`;
    });

    // Выводим в консоль и сохраняем в файл
    console.log(result);
    fs.writeFileSync(outputPath, result, 'utf8');
    console.log(`\nРезультаты сохранены в файл: ${outputPath}`);
} catch (error) {
    console.error('Ошибка:', error.message);
} 