export const timeConverter = () => {
  const a = new Date();
  let year = a.getFullYear();
  let month = a.getMonth() + 1
  let day = a.getDate()
  month = month < 10 ? `0${month}` : month
  day = day < 10 ? `0${day}` : day
  let time = month + '/' + day + '/' + year
  return time;
}

export const statusConversion = (status) => {
  switch (status) {
    case 1:
      return 'Assigned';
    case 2:
      return 'Pending';
    case 3:
      return 'Approved'
    case 4:
      return 'Rejected'
  }
}
export const statusConversionOther = (status) => {
  switch (status) {
    case 1:
      return 'Assigned';
    case 2:
      return 'Approved';
    case 3:
      return 'Rejected'
  }
}

export const slugify = (text) => {
  return text.toString().toLowerCase()
    .replace(/\s+/g, '-')           // Replace spaces with -
    .replace(/[^\w\-]+/g, '')       // Remove all non-word chars
    .replace(/\-\-+/g, '-')         // Replace multiple - with single -
    .replace(/^-+/, '')             // Trim - from start of text
    .replace(/-+$/, '');            // Trim - from end of text
}

export const dateConversion = (date) => {
  return date.substr(0, 10);
}

export const getDate = () => {
  const a = new Date();
  let year = a.getFullYear();
  let month = a.getMonth() + 1
  let day = a.getDate()
  month = month < 10 ? `0${month}` : month
  day = day < 10 ? `0${day}` : day
  let time = year + '-' + month + '-' + day
  return time;
}
export const getPreviousDate = () => {
  const yesterday = new Date();
  yesterday.setDate(yesterday.getDate() - 1);
  let year = yesterday.getFullYear();
  let month = yesterday.getMonth() + 1
  let day = yesterday.getDate()
  month = month < 10 ? `0${month}` : month
  day = day < 10 ? `0${day}` : day
  let time = year + '-' + month + '-' + day
  return time;
}
export const getNextDate = () => {
  const tomorrow = new Date();
  tomorrow.setDate(tomorrow.getDate() + 1);
  let year = tomorrow.getFullYear();
  let month = tomorrow.getMonth() + 1
  let day = tomorrow.getDate()
  month = month < 10 ? `0${month}` : month
  day = day < 10 ? `0${day}` : day
  let time = year + '-' + month + '-' + day
  return time;
}

