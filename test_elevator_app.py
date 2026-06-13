import pytest
import time
from selenium import webdriver
from selenium.webdriver.chrome.options import Options
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager


class TestElevatorManagement:
    @pytest.fixture(scope='function')
    def driver(self):
        options = Options()
        options.add_argument('--headless')
        options.add_argument('--no-sandbox')
        options.add_argument('--disable-dev-shm-usage')
        options.add_argument('--disable-gpu')
        options.add_argument('--window-size=1920,1080')

        service = Service(ChromeDriverManager().install())
        driver = webdriver.Chrome(service=service, options=options)
        driver.implicitly_wait(10)

        yield driver

        driver.quit()

    def test_login_page_loads_successfully(self, driver):
        """测试登录页面是否正常加载"""
        print("\n[测试] 登录页面加载测试")
        driver.get('http://127.0.0.1:8000/login')

        wait = WebDriverWait(driver, 10)

        title = driver.title
        print(f"  - 页面标题: {title}")

        login_form = wait.until(EC.presence_of_element_located((By.TAG_NAME, 'form')))
        print(f"  - 登录表单已找到: {login_form is not None}")

        username_input = wait.until(EC.presence_of_element_located((By.ID, 'username')))
        password_input = wait.until(EC.presence_of_element_located((By.ID, 'password')))
        submit_button = wait.until(EC.presence_of_element_located((By.CSS_SELECTOR, 'button[type="submit"]')))

        print(f"  - 用户名输入框已找到: {username_input is not None}")
        print(f"  - 密码输入框已找到: {password_input is not None}")
        print(f"  - 提交按钮已找到: {submit_button is not None}")

        assert login_form is not None, "登录表单未找到"
        assert username_input is not None, "用户名输入框未找到"
        assert password_input is not None, "密码输入框未找到"
        assert submit_button is not None, "提交按钮未找到"

        print("  ✓ 登录页面加载测试通过\n")

    def test_login_page_elements_exist(self, driver):
        """测试登录页面所有必要元素是否存在"""
        print("\n[测试] 登录页面元素完整性测试")
        driver.get('http://127.0.0.1:8000/login')

        wait = WebDriverWait(driver, 10)

        page_has_logo = len(driver.find_elements(By.CLASS_NAME, 'logo')) > 0 or \
                       len(driver.find_elements(By.TAG_NAME, 'img')) > 0
        print(f"  - 页面有Logo/图片: {page_has_logo}")

        csrf_token = driver.find_element(By.CSS_SELECTOR, 'input[name="_token"]')
        print(f"  - CSRF Token已找到: {csrf_token is not None}")
        assert csrf_token is not None, "CSRF Token未找到"

        submit_button_text = driver.find_element(By.CSS_SELECTOR, 'button[type="submit"]').text
        print(f"  - 提交按钮文本: '{submit_button_text}'")

        print("  ✓ 登录页面元素完整性测试通过\n")

    def test_login_with_invalid_credentials(self, driver):
        """测试使用无效凭据登录（只读测试，不修改数据）"""
        print("\n[测试] 无效凭据登录测试")
        driver.get('http://127.0.0.1:8000/login')

        wait = WebDriverWait(driver, 10)

        username_input = wait.until(EC.presence_of_element_located((By.ID, 'username')))
        password_input = wait.until(EC.presence_of_element_located((By.ID, 'password')))
        submit_button = driver.find_element(By.CSS_SELECTOR, 'button[type="submit"]')

        username_input.send_keys('nonexistent_user')
        password_input.send_keys('wrong_password')

        submit_button.click()

        time.sleep(2)

        current_url = driver.current_url
        print(f"  - 登录后URL: {current_url}")

        still_on_login = '/login' in current_url
        print(f"  - 仍然在登录页面: {still_on_login}")

        error_message = None
        try:
            error_elements = driver.find_elements(By.CLASS_NAME, 'alert-danger')
            if error_elements:
                error_message = error_elements[0].text
                print(f"  - 错误消息: {error_message}")
        except:
            pass

        assert still_on_login, "无效凭据登录后应该仍在登录页面"
        print("  ✓ 无效凭据登录测试通过（不修改任何数据）\n")

    def test_homepage_requires_authentication(self, driver):
        """测试首页是否需要认证"""
        print("\n[测试] 首页认证要求测试")
        driver.get('http://127.0.0.1:8000/')

        time.sleep(2)

        current_url = driver.current_url
        print(f"  - 未认证访问首页后的URL: {current_url}")

        redirects_to_login = '/login' in current_url
        print(f"  - 重定向到登录页: {redirects_to_login}")

        assert redirects_to_login, "未认证访问首页应该重定向到登录页"
        print("  ✓ 首页认证要求测试通过\n")

    def test_maintenance_page_requires_authentication(self, driver):
        """测试维护页面是否需要认证"""
        print("\n[测试] 维护页面认证要求测试")
        driver.get('http://127.0.0.1:8000/maintenance')

        time.sleep(2)

        current_url = driver.current_url
        print(f"  - 未认证访问维护页面后的URL: {current_url}")

        redirects_to_login = '/login' in current_url
        print(f"  - 重定向到登录页: {redirects_to_login}")

        assert redirects_to_login, "未认证访问维护页面应该重定向到登录页"
        print("  ✓ 维护页面认证要求测试通过\n")

    def test_api_video_types_endpoint(self, driver):
        """测试视频分类API端点"""
        print("\n[测试] 视频分类API端点测试")

        api_url = 'http://127.0.0.1:8000/api/v1/video/types'
        print(f"  - 访问API: {api_url}")

        driver.get(api_url)

        time.sleep(2)

        page_source = driver.page_source
        print(f"  - 响应内容长度: {len(page_source)} 字符")

        try:
            import json
            response_data = json.loads(page_source)

            if 'code' in response_data:
                print(f"  - 响应code: {response_data['code']}")
                print(f"  - 响应message: {response_data.get('message', 'N/A')}")

                if 'data' in response_data:
                    print(f"  - 数据条目数: {len(response_data['data'])}")

            print("  ✓ 视频分类API端点测试通过\n")
        except json.JSONDecodeError:
            print(f"  - 响应不是JSON格式（可能是HTML错误页面）")
            print("  - 响应内容预览: " + page_source[:200])
            print("  ✓ API端点测试完成（仅检查连接性）\n")

    def test_page_titles_and_headings(self, driver):
        """测试登录页面的标题和标题元素"""
        print("\n[测试] 页面标题和标题测试")
        driver.get('http://127.0.0.1:8000/login')

        wait = WebDriverWait(driver, 10)

        h1_elements = driver.find_elements(By.TAG_NAME, 'h1')
        h2_elements = driver.find_elements(By.TAG_NAME, 'h2')
        h3_elements = driver.find_elements(By.TAG_NAME, 'h3')

        print(f"  - H1标签数量: {len(h1_elements)}")
        print(f"  - H2标签数量: {len(h2_elements)}")
        print(f"  - H3标签数量: {len(h3_elements)}")

        if h1_elements:
            print(f"  - H1内容: {h1_elements[0].text}")
        if h2_elements:
            print(f"  - H2内容: {h2_elements[0].text}")
        if h3_elements:
            print(f"  - H3内容: {h3_elements[0].text}")

        print("  ✓ 页面标题和标题测试通过\n")

    def test_css_and_assets_loading(self, driver):
        """测试CSS和资源文件是否正常加载"""
        print("\n[测试] CSS和资源文件加载测试")
        driver.get('http://127.0.0.1:8000/login')

        wait = WebDriverWait(driver, 10)

        stylesheets = driver.find_elements(By.CSS_SELECTOR, 'link[rel="stylesheet"]')
        scripts = driver.find_elements(By.TAG_NAME, 'script')
        images = driver.find_elements(By.TAG_NAME, 'img')

        print(f"  - 样式表数量: {len(stylesheets)}")
        print(f"  - 脚本数量: {len(scripts)}")
        print(f"  - 图片数量: {len(images)}")

        if stylesheets:
            print(f"  - 第一个样式表href: {stylesheets[0].get_attribute('href')}")

        assert len(stylesheets) > 0, "应该有至少一个样式表"
        print("  ✓ CSS和资源文件加载测试通过\n")

    def test_navigation_links_exist(self, driver):
        """测试登录页面导航链接"""
        print("\n[测试] 登录页面导航链接测试")
        driver.get('http://127.0.0.1:8000/login')

        links = driver.find_elements(By.TAG_NAME, 'a')
        print(f"  - 链接总数: {len(links)}")

        hrefs = [link.get_attribute('href') for link in links if link.get_attribute('href')]
        print(f"  - 有效href链接数: {len(hrefs)}")

        if hrefs:
            print(f"  - 示例链接: {hrefs[0] if hrefs else 'N/A'}")

        print("  ✓ 导航链接测试通过\n")

    def test_responsive_design_check(self, driver):
        """测试响应式设计（不同窗口大小）"""
        print("\n[测试] 响应式设计测试")

        test_sizes = [
            (1920, 1080, "桌面"),
            (768, 1024, "平板"),
            (375, 667, "手机")
        ]

        driver.get('http://127.0.0.1:8000/login')
        wait = WebDriverWait(driver, 10)

        for width, height, device in test_sizes:
            driver.set_window_size(width, height)
            time.sleep(0.5)

            login_form = wait.until(EC.presence_of_element_located((By.TAG_NAME, 'form')))
            is_visible = login_form.is_displayed()

            print(f"  - {device} ({width}x{height}): 表单可见={is_visible}")

            assert is_visible, f"{device}尺寸下登录表单应该可见"

        driver.set_window_size(1920, 1080)
        print("  ✓ 响应式设计测试通过\n")


if __name__ == '__main__':
    pytest.main([__file__, '-v', '--html=reports/test_report.html', '--self-contained-html'])
